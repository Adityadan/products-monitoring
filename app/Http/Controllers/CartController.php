<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $stock = \App\Models\Product::find($productId)->oh;
        if ($stock < $quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Out of stock',
            ]);
        }
        // Ambil cart dari session atau buat baru jika tidak ada
        $cart = Session::get('cart', []);

        // Cek apakah produk sudah ada di cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        // Simpan kembali ke session
        Session::put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'cart' => $cart,
        ]);
    }
    public function showCart()
    {
        $cart = CartHelper::getCart();
        $cart_content = '';
        foreach ($cart as $item) {
            $imageSrc = $item['image'] ? asset('storage/' . $item['image']) : asset('no-image.jpg');

            $cart_content .= '<div class="row gx-x1 mx-0 align-items-center border-bottom border-200">
                                <div class="col-8 py-3 px-x1">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);">
                                            <img class="img-fluid rounded-1 me-3 d-none d-md-block"
                                                src="' . $imageSrc . '" alt="Product Image" width="60" />
                                        </a>
                                        <div class="flex-1">
                                            <h5 class="fs-9">
                                                <a class="text-900" href="javascript:void(0);">
                                                ' . $item['name'] . '
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 py-3 px-x1">
                                    <div class="row align-items-center">
                                        <div
                                            class="col-md-8 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                                            <p>' . $item['quantity'] . '</p>
                                        </div>
                                        <!-- Price -->
                                        <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                                            ' . number_format($item['price'], 0, ',', '.') .'
                                        </div>
                                    </div>
                                </div>
                            </div>';
        }

        return response()->json([
            'cart' => $cart,
            'cart_content' => $cart_content,
        ]);
    }

    public function loadCart()
    {
        $cart = CartHelper::getCart();
        return response()->json([
            'cart' => $cart,
        ]);
    }
}
