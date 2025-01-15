<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\LogImport;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $query = DB::table('sales as s')
                ->rightJoin('products as p', function ($join) {
                    $join->on('s.kode_dealer', '=', 'p.kode_dealer')
                        ->on('s.no_part', '=', 'p.no_part');
                })
                ->leftJoin('dealers as d', 's.kode_dealer', '=', 'd.kode')
                ->select(
                    'd.ahass as item',
                    'p.nama_part as item_name',
                    DB::raw("COALESCE(SUM(CASE WHEN TO_CHAR(s.periode, 'YYYY-MM') = TO_CHAR((CURRENT_DATE - INTERVAL '5 MONTH'), 'YYYY-MM') THEN s.qty END), 0) AS month_1"),
                    DB::raw("COALESCE(SUM(CASE WHEN TO_CHAR(s.periode, 'YYYY-MM') = TO_CHAR((CURRENT_DATE - INTERVAL '4 MONTH'), 'YYYY-MM') THEN s.qty END), 0) AS month_2"),
                    DB::raw("COALESCE(SUM(CASE WHEN TO_CHAR(s.periode, 'YYYY-MM') = TO_CHAR((CURRENT_DATE - INTERVAL '3 MONTH'), 'YYYY-MM') THEN s.qty END), 0) AS month_3"),
                    DB::raw("COALESCE(SUM(CASE WHEN TO_CHAR(s.periode, 'YYYY-MM') = TO_CHAR((CURRENT_DATE - INTERVAL '2 MONTH'), 'YYYY-MM') THEN s.qty END), 0) AS month_4"),
                    DB::raw("COALESCE(SUM(CASE WHEN TO_CHAR(s.periode, 'YYYY-MM') = TO_CHAR((CURRENT_DATE - INTERVAL '1 MONTH'), 'YYYY-MM') THEN s.qty END), 0) AS month_5"),
                    DB::raw("COALESCE(SUM(CASE WHEN TO_CHAR(s.periode, 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM') THEN s.qty END), 0) AS month_6"),
                    DB::raw("COALESCE(ROUND(AVG(s.qty), 2), 0) AS average_sales"),
                    DB::raw("COALESCE(SUM(p.oh), 0) AS stock")
                )
                ->whereNull('s.deleted_at')
                ->whereNull('p.deleted_at')
                ->where(DB::raw("TO_CHAR(s.periode, 'YYYY-MM')"), '>=', DB::raw("TO_CHAR((CURRENT_DATE - INTERVAL '5 MONTH'), 'YYYY-MM')"))
                ->where(DB::raw("TO_CHAR(s.periode, 'YYYY-MM')"), '<=', DB::raw("TO_CHAR(CURRENT_DATE, 'YYYY-MM')"));
                if (!auth()->user()->hasRole('main_dealer')) {
                    $query->where('s.kode_dealer', $kode_dealer);
                }
                $query->groupBy('p.no_part', 'p.nama_part', 'd.ahass')
                ->orderBy('p.no_part')
                ->get();

            $data = $query->get()->toArray();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function import(Request $request)
    {
        $fileName = $request->fileName;
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
            // Validasi untuk main_dealer
            $validator = Validator::make($request->all(), [
                'kode_dealer' => 'required|string',
                'periode' => 'required',
                'looping' => 'required|integer',
            ]);
        } else {
            // Validasi untuk selain main_dealer
            $validator = Validator::make($request->all(), [
                'periode' => 'required',
                'looping' => 'required|integer',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon periksa kembali inputan Anda.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kodeDealer = $request->kode_dealer;
        $periode = \Carbon\Carbon::createFromFormat('m-Y', $request->periode)->startOfMonth()->format('Y-m-d');
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
            $this->handleFileAndLog($request, $kodeDealer, $timestamp, $fileName);
            if ($user->hasRole('main_dealer')) {
                Sales::where('kode_dealer', $kodeDealer)->where('periode', $periode)->delete();
            }
            Sales::where('kode_dealer',  $dealerCode)->where('periode', $periode)->delete();
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
                'periode' => $periode,
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

    private function handleFileAndLog(Request $request, $timestamp, $fileName)
    {
        LogImport::create([
            'file_name' => $fileName,
            'file_type' => 'sales',
            'status' => 'success',
            'message' => 'Data imported successfully.',
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }
}
