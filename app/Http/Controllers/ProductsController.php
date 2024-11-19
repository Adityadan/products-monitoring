<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\Dealer;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    private $previewData = [];
    public function index(Request $request)
    {
        return view('product.index');
    }

    public function productList(Request $request)
    {
        $products = Product::with('dealer')->get();
        return view('product.list', compact('products'));
    }
    public function import(Request $request, ProductImport $import)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
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

    public function uploadFile(Request $request, ProductImport $import)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        // Proses file untuk pratinjau
        $import('priview');
        Excel::import($import, $request->file('file'));

        // Ambil data pratinjau dari import
        $this->previewData = $import->getPreviewData();
        // Tampilkan pratinjau dalam tabel
        return response()->json([
            'success' => true,
            'data' => $this->previewData,
        ]);
    }

}
