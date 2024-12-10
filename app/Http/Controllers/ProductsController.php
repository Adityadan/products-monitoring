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
    public function index()
    {
        $no_part = Product::select('no_part')->distinct()->get()->toArray();
        $filters = [
            ['value' => null, 'text' => 'None'],
            ['value' => 'name_asc', 'text' => 'Sort by Name (A-Z)'],
            ['value' => 'name_desc', 'text' => 'Sort by Name (Z-A)'],
            // ['value' => 'price_asc', 'text' => 'Sort by Price (0-9)'],
            // ['value' => 'price_desc', 'text' => 'Sort by Price (9-0)']
            ['value' => 'price_asc', 'text' => 'Lowest Price'],
            ['value' => 'price_desc', 'text' => 'Highest Price']
        ];

        $stock_filter = [
            ['value' => null, 'text' => 'All'],
            ['value' => 'in_stock', 'text' => 'In Stock'],
            ['value' => 'out_of_stock', 'text' => 'Out of Stock']
        ];

        return view('product.index', compact('filters', 'stock_filter','no_part'));
    }

    public function productList(Request $request)
    {
        $sort = $request->get('sort'); // Mengambil filter sort dari request
        $search = $request->get('search'); // Mengambil filter pencarian dari request
        $stock = $request->get('stock'); // Mengambil filter stock dari request
        $products = Product::with('dealer')->with('product_images'); // Relasi 'dealer' harus sesuai dengan model Anda
        $no_part = $request->get('no_part');
        // Filter pencarian
        if ($search) {
            $lowerSearch = strtolower($search);
            $products->where(function ($query) use ($lowerSearch) {
                $query->whereRaw('LOWER(nama_part) LIKE ?', ['%' . $lowerSearch . '%'])
                    ->orWhereRaw('LOWER(no_part) LIKE ?', ['%' . $lowerSearch . '%']);
            });
        }
        // Filter stok
        if ($stock) {
            $products->where(function ($query) use ($stock) {
                if ($stock === 'in_stock') {
                    $query->where('oh', '>', 0);
                } elseif ($stock === 'out_of_stock') {
                    $query->where('oh', '=', 0);
                }
            });
        }

        // Filter pengurutan
        switch ($sort) {
            case 'name_asc':
                $products->orderBy('nama_part', 'asc');
                break;
            case 'name_desc':
                $products->orderBy('nama_part', 'desc');
                break;
            case 'price_asc':
                $products->orderBy('standard_price_moving_avg_price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('standard_price_moving_avg_price', 'desc');
                break;
            default:
                $products->orderBy('nama_part', 'asc');
                break;
        }
        if ($no_part) {
            $products->whereIn('no_part', $no_part);
        }
        // Pagination
        $products = $products->paginate(9);
        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'current_range' => $products->currentPage() * 9 - 9 . '-' . $products->currentPage() * 9,
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
        ]);
    }
}
