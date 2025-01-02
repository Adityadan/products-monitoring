<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Imports\ProductMainDealerImport;
use App\Models\LogImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DealerProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('dealer-product.index');
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
            $data = Product::select(['id', 'kode_dealer', 'no_part', 'nama_part', 'nama_gudang', 'oh']);
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {

            if (auth()->user()->hasRole('main_dealer')) {
                $import = new ProductMainDealerImport('import');
            } else {
                $import = new ProductImport('import');
            }
            Excel::import($import, $request->file('file'));
            LogImport::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_type' => 'product',
                'file_path' => $request->file('file')->store('excel-import'),
                'status' => 'success',
                'message' => 'Data imported successfully.',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at' => now(),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data imported successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Batas ukuran file maksimal 10MB
        ]);

        try {
            $importType = 'preview';
            $file = $request->file('file');
            $limit = 500; // Batasi preview hanya untuk 500 baris pertama

            if (auth()->user()->hasRole('main_dealer')) {
                $import = new ProductMainDealerImport($importType);
                $data = Excel::toCollection($import, $file); // Membaca semua data

                $previewData = $data->first()->take($limit)->map(function ($row) {
                    $row[1] = str_replace('-', '', $row[1]);
                    return [
                        'kode_dealer' => auth()->user()->kode_dealer,
                        'no_part' => $row[1],
                        'nama_part' => $row[2],
                        'oh' => $row[4],
                        'standard_price_moving_avg_price' => $row[5],
                    ];
                })->toArray();
            } else {
                $import = new ProductImport($importType);
                Excel::import($import, $file);
                $previewData = $import->getPreviewData();
            }
            return response()->json([
                'success' => true,
                'data' => $previewData,
                'message' => "Menampilkan $limit baris pertama dari file.",
                'is_main_dealer' => auth()->user()->hasRole('main_dealer'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file for preview.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
