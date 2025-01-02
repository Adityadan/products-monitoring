<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class CartHelper
{
    public static function getCart()
    {
        $cart = Session::get('cart', []);
        foreach ($cart as $key => $value) {
            $product = \App\Models\Product::with(['detail_product', 'dealer'])->find($value['product_id']);
            // $product = \App\Models\Product::with(['product_images', 'dealer'])->find($value['product_id']);
            $cart[$key]['no_part'] = $product->no_part;
            $cart[$key]['kode_dealer'] = $product->kode_dealer;
            $cart[$key]['name'] = $product->nama_part;
            $cart[$key]['dealer'] = $product->dealer->ahass;
            $cart[$key]['price'] = $product->standard_price_moving_avg_price;
            $cart[$key]['subtotal'] = $product->standard_price_moving_avg_price * $value['quantity'];
            $cart[$key]['quantity'] = $value['quantity'];
            $cart[$key]['image'] = $product->detail_product[0]->image ?? '';
            // $cart[$key]['image'] = $product->product_images[0]->image ?? '';
        }

        return $cart;
    }

}
