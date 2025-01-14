<?php

namespace App\Http\Controllers;

use App\Imports\DealersImport;
use App\Models\Dealer;
use App\Models\LogImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;


class DealersController extends Controller
{
    public function index()
    {
        return view('dealer.index');
    }

    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $dealers = Dealer::select(['id', 'kode', 'ahass', 'kota_kab', 'kecamatan', 'status', 'se_area', 'group', 'kode_customer']);

            $dataTable = DataTables::of($dealers)
                ->addIndexColumn();

            if (auth()->user()->hasRole('main_dealer')) {
                $dataTable->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-primary edit-dealer" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#edit-dealer-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-dealer" data-id="' . $row->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                });
            }

            return $dataTable->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:10|unique:dealers,kode',
            'ahass' => 'required|string|max:100',
            'kota_kab' => 'required|string|max:50',
            'kecamatan' => 'required|string|max:50',
            'status' => 'required|string|max:20',
            'se_area' => 'nullable|string|max:50',
            'group' => 'nullable|string|max:50',
            'kode_customer' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Dealer::create($request->all());

        return response()->json(['message' => 'Dealer berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $dealer = Dealer::findOrFail($id);

        return response()->json($dealer);
    }

    public function update(Request $request, $id)
    {
        $dealer = Dealer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:10|unique:dealers,kode,' . $id,
            'ahass' => 'required|string|max:100',
            'kota_kab' => 'required|string|max:50',
            'kecamatan' => 'required|string|max:50',
            'status' => 'required|string|max:20',
            'se_area' => 'nullable|string|max:50',
            'group' => 'nullable|string|max:50',
            'kode_customer' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $dealer->update($request->all());

        return response()->json(['message' => 'Dealer berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $dealer = Dealer::findOrFail($id);
        $dealer->delete();

        return response()->json(['message' => 'Dealer berhasil dihapus.']);
    }

    public function importExcelOld(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new DealersImport, $request->file('file'));

            LogImport::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_path' => $request->file('file')->store('excel-import'),
                'file_type' => 'dealers',
                'status' => 'success',
                'message' => 'Data Dealer berhasil diimpor.',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data Dealer berhasil diimpor.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor data Dealer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function importExcel(Request $request)
    {
        $data = json_decode($request->data);
        $fileName = $request->file_name;

        try {

            foreach ($data as $value) {
                $kode = str_pad($value[0], 5, '0', STR_PAD_LEFT);
                $dealerData = [
                    'kode' => $kode,
                    'kode_customer' => $value[1],
                    'ahass' => $value[2],
                    'kota_kab' => $value[3],
                    'kecamatan' => $value[4],
                    'status' => $value[5],
                    'se_area' => $value[6],
                    'group' => $value[7],
                ];

                // Update or insert dealer based on 'kode'
                Dealer::updateOrCreate(
                    ['kode' => $kode],
                    $dealerData
                );
            }

            $this->logImport($fileName, 'success', 'Data Dealer berhasil diimpor.');
            return response()->json([
                'success' => true,
                'message' => 'Data Dealer berhasil diimpor.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor data Dealer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function logImport ($fileName, $status, $message) {
        LogImport::create([
            'file_name' => $fileName,
            'file_type' => 'dealers',
            'status' => $status,
            'message' => $message,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
