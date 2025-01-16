<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\LogImport;
use App\Models\ROD;
use App\Models\SummaryRod;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RodController extends Controller
{
    protected $kode_customer;

    public function __construct()
    {
        $user = auth()->user();
        if ($user && $user->kode_dealer) {
            $this->kode_customer = Dealer::where('kode', $user->kode_dealer)->first()->kode_customer;
        } else {
            $this->kode_customer = null;
        }
    }
    public function index()
    {
        return view('rod.index');
    }

    public function datatable()
    {
        $query = ROD::query();
        if (!auth()->user()->hasRole('main_dealer')) {
            $query->where('kode_customer', $this->kode_customer);
        }
        $data = $query->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('actions', function ($data) {
                $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm">Edit</button>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Delete</button>';
                return $button;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function import(Request $request)
    {
        // Validasi data dari permintaan
        $validatedData = $request->validate([
            'periode' => 'required|date_format:m-Y',
            'file_name' => 'required|string',
            'data' => 'required|json',
            'looping' => 'required|integer|min:1',
            'rod_type' => 'required|string',
        ]);

        // Konversi dan persiapan data
        $periode = \Carbon\Carbon::createFromFormat('m-Y', $validatedData['periode'])
            ->startOfMonth()
            ->format('Y-m-d');
        $fileName = $validatedData['file_name'];
        $data = json_decode($validatedData['data'], true);
        $looping = $validatedData['looping'];
        $rodType = $validatedData['rod_type'];

        // Validasi tambahan untuk data yang dikirim
        if (!is_array($data) || empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data format or empty data.',
            ], 422);
        }

        // Hapus data lama jika ini adalah iterasi pertama
        if ($looping == 1) {
            $cekTargetExist = Target::whereNull('deleted_at')->exists();
            if (!$cekTargetExist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Target Belum Di Upload. Silahkan Upload Data Target Terlebih Dahulu.',
                ], 404);
            }

            ROD::where('periode', $periode)
                ->where('rod_type', $rodType)
                ->delete();

            $this->handleFileAndLog($fileName, 'success', 'Old data deleted successfully.');
        }
        if ($rodType == 'non_so') {
            // Persiapan data untuk dimasukkan ke database
            $insertData = array_filter(array_map(function ($value) use ($periode, $rodType) {
                if (!in_array($value[3] ?? '', ['OIL', 'HGP', 'APP'])) {
                    return null;
                }
                return [
                    'kode_customer' => $value[0] ?? null,
                    'customer_name' => $value[1] ?? null,
                    'cost_amount' => $value[2] ?? null,
                    'mat_type' => $value[3] ?? null,
                    'periode' => $periode,
                    'rod_type' => $rodType,
                    'created_at' => now(),
                ];
            }, $data));
        }

        if ($rodType == 'so') {
            // Persiapan data untuk dimasukkan ke database
            $insertData = array_filter(array_map(function ($value) use ($periode, $rodType) {
                if (!in_array($value[2] ?? '', ['OIL', 'HGP', 'APP'])) {
                    return null;
                }
                $getCustomerName = Target::select('customer_name')->where('kode_customer', $value[0])
                    ->first();
                $getCustomerName = $getCustomerName['customer_name'];
                return [
                    'kode_customer' => $value[0] ?? null,
                    'customer_name' => $getCustomerName ?? null,
                    'cost_amount' => $value[1] ?? null,
                    'mat_type' => $value[2] ?? null,
                    'periode' => $periode,
                    'rod_type' => $rodType,
                    'created_at' => now(),
                ];
            }, $data));
        }
        // Masukkan data ke database
        try {
            ROD::insert($insertData);
            $this->handleFileAndLog($fileName, 'success', 'Data imported successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Data imported successfully.',
            ]);
        } catch (\Exception $e) {
            // Log error dan kembalikan respon
            $this->handleFileAndLog($fileName, 'failed', $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to import data. ' . $e->getMessage(),
            ], 500);
        }
    }
    private function handleFileAndLog($fileName, $status, $message)
    {
        LogImport::create([
            'file_name' => $fileName,
            'file_type' => 'rod',
            'status' => $status,
            'message' => $message,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function sumDashboardRod()
    {
        try {
            $data = DB::table('rod')
                ->select(
                    'kode_customer',
                    'customer_name',
                    'periode',
                    DB::raw("SUM(CASE WHEN mat_type = 'HGP' THEN cost_amount ELSE 0 END) AS total_amount_part"),
                    DB::raw("SUM(CASE WHEN mat_type = 'OIL' THEN cost_amount ELSE 0 END) AS total_amount_oil"),
                    DB::raw("SUM(CASE WHEN mat_type = 'APP' THEN cost_amount ELSE 0 END) AS total_amount_app")
                )
                ->whereNull('deleted_at')
                ->groupBy('kode_customer', 'customer_name', 'periode')
                ->orderBy('kode_customer')
                ->orderBy('periode')
                ->get();

            $summaryData = $data->map(function ($item) {
                return [
                    'kode_customer' => $item->kode_customer,
                    'customer_name' => $item->customer_name,
                    'periode' => $item->periode,
                    'total_amount_part' => $item->total_amount_part,
                    'total_amount_oil' => $item->total_amount_oil,
                    'total_amount_app' => $item->total_amount_app,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Soft delete existing records for the same period
            $periode = $data->first()->periode ?? null;
            if ($periode) {
                SummaryRod::where('periode', $periode)->update(['deleted_at' => now()]);
            }

            SummaryRod::insert($summaryData);

            return response()->json([
                'success' => true,
                'message' => 'Data summarized and inserted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to summarize data. ' . $e->getMessage(),
            ], 500);
        }
    }
}
