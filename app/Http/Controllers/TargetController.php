<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\LogImport;
use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{

    protected $kode_customer;

    public function __construct()
    {
        $this->kode_customer = Dealer::where('kode', auth()->user()->kode_dealer)->first()->kode_customer;
    }

    public function index()
    {
        return view('target.index');
    }

    public function datatable()
    {
        $query = Target::query();
        if (!auth()->user()->hasRole('main_dealer')) {
            $query->where('kode_customer', $this->kode_customer);
        }
        $data = $query->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('target_part', function ($data) {
                return 'Rp.'. number_format($data->target_part, 2, ',', '.');
            })
            ->editColumn('target_oli', function ($data) {
                return 'Rp.'. number_format($data->target_oli, 2, ',', '.');
            })
            ->editColumn('target_app', function ($data) {
                return 'Rp.'. number_format($data->target_app, 2, ',', '.');
            })
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
        $validatedData = $request->validate([
            'periode' => 'required',
            'file_name' => 'required|string',
            'data' => 'required|json',
            'looping' => 'required|integer|min:1',
        ]);

        // $periode = $validatedData['periode'];
        $periode = \Carbon\Carbon::createFromFormat('m-Y', $validatedData['periode'])->startOfMonth()->format('Y-m-d');

        $fileName = $validatedData['file_name'];
        $loop = $validatedData['looping'];
        $data = json_decode($validatedData['data'], true);

        // Validasi format data setelah decoding
        if (!is_array($data) || empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data format or empty data.',
            ], 400);
        }

        try {
            if ($loop == 1) {
                // Hapus data lama untuk bulan yang sama pada loop pertama
                Target::where('periode', $periode)->delete();
                // Log proses file
                $this->handleFileAndLog($fileName, 'success', 'Data imported successfully.');
            }

            // Mapping data untuk dimasukkan ke dalam database
            $preparedData = array_map(function ($item) use ($periode) {
                return [
                    'kode_customer' => $item[0] ?? null,
                    'customer_name' => $item[1] ?? null,
                    'channel' => $item[2] ?? null,
                    'target_part' => $item[3] ?? null,
                    'target_oli' => $item[4] ?? null,
                    'target_app' => $item[5] ?? null,
                    'periode' => $periode,
                ];
            }, $data);

            // Masukkan data ke database
            Target::insert($preparedData);

            return response()->json([
                'success' => true,
                'message' => 'Data imported successfully.',
            ]);
        } catch (\Exception $e) {
            // Log error jika terjadi kegagalan
            $this->handleFileAndLog($fileName, 'error', $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during data import: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function handleFileAndLog($fileName, $status, $message)
    {
        LogImport::create([
            'file_name' => $fileName,
            'file_type' => 'target',
            'status' => $status,
            'message' => $message,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

}
