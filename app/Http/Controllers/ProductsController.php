<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
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
        $products = Product::all();
        return view('product.list', compact('products'));
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ProductImport, $request->file('file'));
            // return back()->with('success', 'Data imported successfully.');
            return redirect()->route('product.index')->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            // return back()->withErrors(['error' => $e->getMessage()]);
            return redirect()->route('product.index')->withErrors(['error' => $e->getMessage()]);
        }
    }
}
