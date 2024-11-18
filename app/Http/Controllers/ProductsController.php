<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\Dealer;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        return view('product.index');
    }

    public function productList(Request $request)
    {
        // dd('masok');
        $products = Product::with('dealer')->get();
        // $products = Dealer::with('products')->where('kode', '00762')->get();
        // $products = Dealer::with(['products' => function ($query) {
        //     $query->where('kode', '00762'); // Ganti 'your_value' dengan nilai yang Anda inginkan
        // }])->get();
        return view('product.list', compact('products'));
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ProductImport, $request->file('file'));
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
}
