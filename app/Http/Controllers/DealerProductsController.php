<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
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
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $data = Product::select(['id', 'kode_dealer', 'no_part', 'nama_part', 'nama_gudang', 'oh']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-primary edit-product" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#edit-product-modal">Edit</button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-product" data-id="' . $row->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
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
            // Buat instance ProductImport dengan tipe 'import'
            $import = new ProductImport('import');
            Excel::import($import, $request->file('file'));

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
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Buat instance ProductImport dengan tipe 'preview'
            $import = new ProductImport('preview');
            Excel::import($import, $request->file('file'));

            // Ambil data pratinjau dari import
            $previewData = $import->getPreviewData();

            return response()->json([
                'success' => true,
                'data' => $previewData,
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
