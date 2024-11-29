<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\Dealer;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        return view('product.index');
    }

    public function productList(Request $request)
    {
        $products = Product::with('dealer')->paginate(6); // 6 produk per halaman
        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }
}
