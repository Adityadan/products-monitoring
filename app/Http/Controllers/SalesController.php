<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\LogImport;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index()
    {
        $dealer = Dealer::select('kode', 'ahass')->orderBy('ahass')->get();
        return view('sales.index', compact('dealer'));
    }

    public function datatable(Request $request)
    {
        $user = auth()->user();
        $kode_dealer = $user->kode_dealer;

        if (empty($kode_dealer)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode dealer tidak ditemukan. Silahkan Mengisi Kode Dealer Anda.',
            ], 500);
        }

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $data = Sales::select([
                'id',
                'kode_dealer',
                'customer_master_sap',
                'no_part',
                'kategori_part',
                'qty',
                'created_by',
            ]);
            if (!$user->hasRole('main_dealer')) {
                $data->where('kode_dealer', $kode_dealer);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    // $editBtn = '<button class="btn btn-sm btn-primary edit-product" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#edit-product-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-product" data-id="' . $row->id . '">Delete</button>';
                    return /* $editBtn . ' ' . */ $deleteBtn;
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function import(Request $request)
    {
        $user = auth()->user();
        $dealerCode = $user['kode_dealer'];
        $timestamp = now();

        $data = json_decode($request->data, true);
        if (!$data || !is_array($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid atau kosong.',
            ], 422);
        }

        if ($user->hasRole('main_dealer')) {

            // Validasi umum
            $validator = Validator::make($request->all(), [
                'kode_dealer' => 'required|string',
                'date_upload' => 'required|date',
                'looping' => 'required|integer',
            ], [
                'kode_dealer.required' => 'Kode Dealer wajib diisi.',
                'date_upload.required' => 'Tanggal upload wajib diisi.',
                'date_upload.date' => 'Tanggal upload harus berupa format tanggal yang valid.',
                'looping.required' => 'Looping tidak boleh kosong.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mohon periksa kembali inputan Anda.',
                    'errors' => $validator->errors(),
                ], 422);
            }
        }

        $kodeDealer = $request->kode_dealer;
        $dateUpload = $request->date_upload;

        // Validasi tambahan untuk pengguna dengan role main_dealer
        if ($user->hasRole('main_dealer')) {
            if ($kodeDealer != $data[0][0]) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Dealer Tidak Sesuai.',
                ], 400);
            }
        }
        // Proses hanya dilakukan jika looping == 1
        if ($request->looping == 1) {
            $this->handleFileAndLog($request, $kodeDealer, $timestamp);
            if ($user->hasRole('main_dealer')) {
                Sales::where('kode_dealer', $kodeDealer)->delete();
            }
            Sales::where('kode_dealer',  $dealerCode)->delete();
        }
        // Persiapan data untuk batch insert
        $listCreate = [];
        foreach ($data as $value) {
            $value[2] = str_replace('-', '', $value[2]);
            if (strlen($value[0]) !== 5) {
                continue;
            }

            $listCreate[] = [
                'kode_dealer' => $value[0],
                'customer_master_sap' => $value[1],
                'no_part' => $value[2],
                'nama_part' => $value[3],
                'kategori_part' => $value[4],
                'qty' => ($value[5] ?? 0) + ($value[6] ?? 0),
                'date_upload' => $dateUpload,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        // Simpan data ke database jika ada data yang valid
        if (!empty($listCreate)) {
            Sales::insert($listCreate);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data imported successfully.',
        ]);
    }

    private function handleFileAndLog(Request $request, $timestamp)
    {
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('excel-import');
            LogImport::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_type' => 'product',
                'file_path' => $filePath,
                'status' => 'success',
                'message' => 'Data imported successfully.',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
