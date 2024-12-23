<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Dealer;
use App\Models\Expeditions;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $expeditions = Expeditions::all();
        return view('cart.index', compact('expeditions'));
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
                                                ' . $item['name'] . ' (' . $item['product_id'] . ')
                                                </a>
                                            </h5>

                                            <p class="fs-9 text-500 fs-md--1">
                                                <a class="text-900" href="javascript:void(0);">
                                                ' . $item['dealer'] . '
                                                </a>
                                            </p>
                                            <div class="fs-11 fs-md--1">
                                                <a class="text-danger " id="remove-product" href="javascript:void(0);" data-id="' . $item['product_id'] . '"><i class="far fa-trash-alt me-1"></i>Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 py-3 px-x1">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                                             <div class="input-group input-group-sm flex-nowrap" data-quantity="data-quantity">
                                                <button class="btn btn-sm btn-outline-secondary border-300 px-2 shadow-none qty-minus" data-id="' . $item['product_id'] . '" data-qty="' . $item['quantity'] . '" data-type="minus" ' . ($item['quantity'] == 1 ? 'disabled' : '') . '>-</button>
                                                <input class="form-control text-center px-2 input-spin-none" type="number" min="1" value="' . $item['quantity'] . '" aria-label="Amount (to the nearest dollar)" style="width: 50px" />
                                                <button class="btn btn-sm btn-outline-secondary border-300 px-2 shadow-none qty-plus" data-type="plus" data-id="' . $item['product_id'] . '" data-qty="' . $item['quantity'] . '">+</button>
                                            </div>
                                        </div>
                                        <!-- Price -->
                                        <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                                            Rp.' . number_format($item['subtotal'], 0, ',', '.') . '
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

    /* public function updateQuantity(Request $request)
    {
        $cart = Session::get('cart', []);
        $productId = $request->input('product_id');
        $type = $request->input('type');

        // Pastikan cart tidak kosong dan product_id tersedia
        if (!empty($cart) && isset($cart[$productId])) {
            if ($type === 'plus') {
                // Tambah kuantitas
                $cart[$productId]['quantity']++;
            } elseif ($type === 'minus' && $cart[$productId]['quantity'] > 1) {
                // Kurangi kuantitas jika lebih dari 1
                $cart[$productId]['quantity']--;
            }

            // Perbarui session cart
            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart' => $cart,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart or invalid request',
        ], 400);
    } */

    public function updateQuantity(Request $request)
    {
        $cart = Session::get('cart', []);
        $productId = $request->input('product_id');
        $type = $request->input('type');

        if (!empty($cart) && isset($cart[$productId])) {
            if ($type === 'plus') {
                $cart[$productId]['quantity']++;
            } elseif ($type === 'minus' && $cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            }

            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart' => $cart,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart or invalid request',
        ], 400);
    }

    public function deleteProduct(Request $request)
    {
        $cart = Session::get('cart', []);
        $productId = $request->input('product_id');

        foreach ($cart as $key => $value) {
            if ($value['product_id'] == $productId) {
                unset($cart[$key]);
                break;
            }
        }

        // Reindex array to maintain proper keys
        $cart = array_values($cart);
        Session::put('cart', $cart);

        return response()->json(['success' => true, 'cart' => $cart, 'message' => 'Product deleted successfully']);
    }



    public function loadCart()
    {
        $cart = CartHelper::getCart();
        $cart_content = '';
        $total_price = 0;
        $total_items = 0;
        foreach ($cart as $value) {
            $total_price += $value['subtotal'];
            $total_items += $value['quantity'];
            $imageSrc = $value['image'] ? asset('storage/' . $value['image']) : asset('no-image.jpg');

            $cart_content .= '<div class="row gx-x1 mx-0 align-items-center border-bottom border-200">
                <div class="col-6 py-3 px-x1">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);">
                            <img class="img-fluid rounded-1 me-3 d-none d-md-block" src="' . $imageSrc . '" alt="" width="60" />
                        </a>
                        <div class="flex-1">
                            <h5 class="fs-9">
                                <a class="text-900" href="javascript:void(0);">' . $value['name'] . '</a>
                            </h5>
                            <h6 class="fs-9">
                                <a class="text-900" href="javascript:void(0);">' . $value['dealer'] . '</a>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 py-3 px-x1">
                    <div class="row align-items-center">
                        <div class="col-md-4 d-flex justify-content-end justify-content-md-center order-1 order-md-0">
                            <div>
                                <p>' . $value['quantity'] . '</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                            <p class="mb-0 fs-9">Rp.' . number_format($value['price'], 0, ',', '.') . '</p>
                        </div>
                        <div class="col-md-4 text-end ps-0 order-0 order-md-1 mb-2 mb-md-0 text-600">
                            <p class="mb-0 fs-9">Rp.' . number_format($value['subtotal'], 0, ',', '.') . '</p>
                        </div>
                    </div>
                </div>
            </div>';
        }
        $total = [
            'total_price' => 'Rp.' . number_format($total_price, 0, ',', '.'),
            'total_items' => $total_items
        ];
        return response()->json([
            'cart_content' => $cart_content,
            'total' => $total
        ]);
    }

    public function checkout(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        // Ambil data cart
        $cart = CartHelper::getCart();
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty']);
        }

        // Data pembeli dari request
        $buyerName = $request->input('name');
        $phone = $request->input('phone');
        $shippingAddress = $request->input('address');
        $notes = $request->input('notes', null);

        // Hitung total harga dan total item
        $totalPrice = array_sum(array_column($cart, 'subtotal'));
        $totalItems = array_sum(array_column($cart, 'quantity'));
        $buyer_dealer = auth()->user()->kode_dealer;
        if (empty($buyer_dealer)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode dealer tidak ditemukan, silahkan mengisi kode dealer terlebih dahulu',
            ]);
        }
        DB::beginTransaction();
        try {
            // Simpan setiap item dalam cart ke tabel orders
            foreach ($cart as $item) {
                Order::create([
                    'no_part' => $item['no_part'],
                    'kode_dealer' => $item['kode_dealer'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'total_price' => $totalPrice,
                    'total_items' => $totalItems,
                    'buyer_dealer' => $buyer_dealer,
                    'buyer_name' => $buyerName,
                    'phone' => $phone,
                    'shipping_address' => $shippingAddress,
                    'notes' => $notes,
                ]);
            }

            // Hapus cart setelah sukses checkout
            Session::forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'total_price' => $totalPrice,
                'total_items' => $totalItems,
            ]);
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ]);
        }
    }
}
