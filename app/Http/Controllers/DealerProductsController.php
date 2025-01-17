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

        if (empty($kode_dealer) && !$user->hasRole('superadmin')) {
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

    public function previewNewOld(Request $request)
    {
        // dd($request->all());
        $user = auth()->user();
        $dealerCode = $user['kode_dealer'];
        if ($dealerCode != '00000') {
            return response()->json([
                'status' => true
            ]);
        }

        $data = json_decode($request->data);
        // dd($data);

        $listCreate = [];
        foreach ($data as $product) {
            // checking if header or data from excel (Main Dealer)
            if ($product[0] == 'No.') {
                continue;
            }

            // checking data in DB
            $dataWhere = [
                'kode_dealer' => $dealerCode,
                'no_part' => $product[1]
            ];
            $isExist = Product::query()
                ->where($dataWhere)
                ->count();

            $dataProduct = [
                'kode_dealer' => $dealerCode,
                'no_part' => $product[1],
                'nama_part' => $product[2],
                'oh' => $product[4],
                'standard_price_moving_avg_price' => $product[5]
            ];
            if ($isExist) {
                Product::where($dataWhere)->update($dataProduct);
            } else {
                $now = date('Y-m-d H:i:s');
                $dataProduct['created_at'] = $now;
                $dataProduct['updated_at'] = $now;
                $listCreate[] = $dataProduct;
            }
        }

        if (count($listCreate) > 0) {
            Product::insert($listCreate);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    public function previewNew(Request $request)
    {
        $request->validate([
            'periode' => 'required',
            'fileName' => 'required|string',
            'data' => 'required|json',
            'looping' => 'required|integer',
        ]);

        try {
            $periode = \Carbon\Carbon::createFromFormat('d-m-Y', $request->periode)->format('Y-m-d');
            $fileName = $request->fileName;
            $user = auth()->user();
            $dealerCode = $user['kode_dealer'];
            if (!$dealerCode) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kode dealer tidak ditemukan. Silahkan Mengisi Kode Dealer Anda.'
                ], 400);
            }

            $data = json_decode($request->data);
            if (!is_array($data) || empty($data)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak valid atau kosong.'
                ], 400);
            }

            $isMainDealer = filter_var($request->is_main_dealer, FILTER_VALIDATE_BOOLEAN);
            $listCreate = [];
            $timestamp = now();

            // Jika dealer cabang, pastikan data main dealer sudah ada
            if (!$isMainDealer) {
                $mainDealerProducts = Product::where('kode_dealer', '00000')->exists();
                if (!$mainDealerProducts) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data produk dari main dealer belum tersedia. Harap main dealer mengimpor data terlebih dahulu.'
                    ], 400);
                }
            }
            if ($request->looping == 2) {
                Product::where('kode_dealer', $dealerCode)->where('periode', $periode)->delete();
                LogImport::create([
                    'file_name' => $fileName,
                    'file_type' => 'product',
                    'status' => 'success',
                    'message' => 'Data imported successfully.',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => now(),
                ]);
            }
            foreach ($data as $row) {
                if ($isMainDealer) {
                    $row[1] = str_replace('-', '', $row[1]);

                    // Logika untuk main dealer
                    $dataWhere = [
                        'kode_dealer' => $dealerCode,
                        'nama_part' => $row[2] // Gunakan nama_part untuk penggabungan
                    ];

                    $dataProduct = [
                        'kode_dealer' => $dealerCode,
                        'no_part' => $row[1],
                        'nama_part' => $row[2],
                        'oh' => $row[4], // Stok baru
                        'standard_price_moving_avg_price' => $row[5],
                        'updated_at' => $timestamp,
                        'periode' => $periode,
                    ];

                    // Gabungkan stok jika ada data serupa di $listCreate
                    $index = array_search($row[2], array_column($listCreate, 'nama_part'));
                    if ($index !== false) {
                        $listCreate[$index]['oh'] += $row[4];
                    } else {
                        $dataProduct['created_at'] = $timestamp;
                        $listCreate[] = $dataProduct;
                    }
                } else {

                    // Jika 'Total' terdeteksi, skip baris ini
                    if (isset($row[0]) && strtolower($row[0]) === 'total') {
                        continue;
                    }

                    // Logika untuk non-main dealer
                    $dataWhere = [
                        'kode_dealer' => $row[1],
                        'nama_part' => $row[7] // Gunakan nama_part untuk penggabungan
                    ];

                    // Get the price from the main dealer (kode_dealer = 00000)
                    $mainDealerPrice = Product::query()->where([
                        'kode_dealer' => '00000', // Main dealer code
                        'no_part' => $row[6]
                    ])->value('standard_price_moving_avg_price');

                    // If main dealer price is found, use it
                    $standardPrice = $mainDealerPrice ?? $row[25]; // Use uploaded price if main dealer price is not found


                    $dataProduct = [
                        'no' => $row[0],
                        'kode_dealer' => $row[1],
                        'kode_ba' => $row[2],
                        'customer_master_sap' => $row[3],
                        'group_material' => $row[4],
                        'group_tobpm' => $row[5],
                        'no_part' => $row[6],
                        'nama_part' => $row[7],
                        'rank_part' => $row[8],
                        'discontinue' => $row[9],
                        'kode_gudang' => $row[10],
                        'nama_gudang' => $row[11],
                        'kode_lokasi' => $row[12],
                        'int' => $row[13],
                        'oh' => $row[14], // Stok baru
                        'rsv' => $row[15],
                        'blk' => $row[16],
                        'wip' => $row[17],
                        'bok' => $row[18],
                        'total_exc_int' => $row[19],
                        'stock_days_month' => $row[20],
                        'avg_demand_qty' => $row[21],
                        'avg_demand_amt' => $row[22],
                        'avg_sales_monthly_qty' => $row[23],
                        'avg_sales_monthly_amt' => $row[24],
                        'standard_price_moving_avg_price' => $standardPrice, // Set price from main dealer or uploaded price
                        'invt_amt_exc_int' => $row[26],
                        'periode' => $periode,
                        'updated_at' => $timestamp
                    ];

                    $index = array_search($row[7], array_column($listCreate, 'nama_part'));
                    if ($index !== false) {
                        $listCreate[$index]['oh'] += $row[14];
                    } else {
                        $dataProduct['created_at'] = $timestamp;
                        $listCreate[] = $dataProduct;
                    }
                    // }
                }
            }

            if (!empty($listCreate)) {
                Product::insert($listCreate);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diproses.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
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
