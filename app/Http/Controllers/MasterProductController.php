<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MasterProductController extends Controller
{
    public function index()
    {
        return view('master-product.index');
    }

    public function datatable(Request $request)
    {
        // Check if the request is an AJAX request
        if ($request->ajax()) {
            $data = Product::select('kode_dealer', 'no_part', 'nama_part', 'nama_gudang', 'oh')
                ->distinct()
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-sm btn-primary" data-id="' . $row->no_part . '" data-bs-toggle="modal" data-bs-target="#add-image-modal"><i class="fas fa-image"></i></button>';
                })
                ->rawColumns(['actions']) // Ensure HTML in the actions column is not escaped
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
            // 'description' => 'nullable|string|max:255', // Validasi deskripsi
        ]);
        // try {
            // Proses upload file
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product-images', 'public'); // Simpan di storage/public/product-images
            }

            // Simpan data ke database
            ProductImage::create([
                'image' => $imagePath ?? null,
                // 'description' => $request->input('description'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product image saved successfully!',
            ], 200);
        // } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save product image. Please try again.',
            ], 500);
        // }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $product = Product::findOrFail($id);

            // Jika ada file baru
            if ($request->hasFile('image')) {
                // Hapus file lama jika ada
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                // Simpan file baru
                $imagePath = $request->file('image')->store('product-images', 'public');
                $product->image = $imagePath;
            }

            // Update deskripsi
            $product->description = $request->input('description');
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product image updated successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product image. Please try again.',
            ], 500);
        }
    }
}
