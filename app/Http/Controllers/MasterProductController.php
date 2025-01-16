<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $user = auth()->user();
        $kode_dealer = $user->kode_dealer;

        if (empty($kode_dealer) && !$user->hasRole('superadmin')) {
            return response()->json([
                'success' => false,
                'message' => 'Kode dealer tidak ditemukan. Silahkan Mengisi Kode Dealer Anda.',
            ], 500);
        }

        if ($request->ajax()) {
            $data = Product::select('kode_dealer', 'no_part', 'nama_part', 'nama_gudang', 'oh')
                ->distinct();

            if (!$user->hasRole('main_dealer')) {
                $data->where('kode_dealer', $kode_dealer);
            }

            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-sm btn-primary add-image-product" data-id="' . $row->no_part . '" data-bs-toggle="modal" data-bs-target="#add-image-modal"><i class="fas fa-image"></i></button>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid request.',
        ], 400);
    }

    public function edit($no_part)
    {
        // $product = Product::findOrFail($id);
        $product = Product::with('detail_product')->where('no_part', $no_part)->first();
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function store(Request $request, $no_part)
    {
        // Validasi input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar wajib diunggah
            'functionality' => 'string',
        ]);

        $functionality = strip_tags($request->input('functionality'));
        try {
            // Cari data berdasarkan no_part
            $productImage = DetailProduct::where('no_part', $no_part)->first();
            // Jika data baru dibuat, buat instance baru dan tambahkan created_by
            if (empty($productImage)) {
                $productImage = new DetailProduct();
                $productImage->no_part = $no_part;
                $productImage->created_by = auth()->user()->id;
            }

            // Jika ada file baru yang diunggah
            if ($request->hasFile('image')) {
                // Hapus file lama jika ada
                if ($productImage->image) {
                    Storage::disk('public')->delete($productImage->image);
                }

                // Simpan file baru
                $imagePath = $request->file('image')->store('product-images', 'public');
                $productImage->image = $imagePath;
            }

            // Tambahkan updated_by untuk semua operasi update
            $productImage->updated_by = auth()->user()->id;

            $productImage->functionality = $functionality;
            // Simpan data ke database
            $productImage->save();

            return response()->json([
                'success' => true,
                'message' => $productImage->wasRecentlyCreated
                    ? 'Product image created successfully!'
                    : 'Product image updated successfully!',
                'data' => $productImage,
            ], 200);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Failed to add/update product image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add/update product image. Please try again.',
            ], 500);
        }
    }
}
