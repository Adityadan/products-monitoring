<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query string untuk sorting dan filtering
        $sort = $request->get('sort', 'best_match');  // Default sorting 'best_match'
        $category = $request->get('category');

        // Query builder untuk mengambil data produk dengan kondisi tertentu
        $query = Product::query();

        // Filtering berdasarkan kategori jika ada
        if ($category) {
            $query->where('category', $category);
        }

        // Sorting berdasarkan parameter yang diterima (contoh: price, newest)
        if ($sort == 'price') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('name'); // Default sorting: name
        }

        // Ambil data produk dengan pagination (misalnya, 20 per halaman)
        $products = Product::paginate(12);

        // Kirim data produk ke view
        return view('product.index', compact('products'));
    }

}
