<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class CartHelper
{
    public static function getCart()
    {
        $cart = Session::get('cart', []);
        foreach ($cart as $key => $value) {
            $product = \App\Models\Product::with(['product_images', 'dealer'])->find($value['product_id']);
            $cart[$key]['name'] = $product->nama_part;
            $cart[$key]['dealer'] = $product->dealer->ahass;
            $cart[$key]['price'] = $product->standard_price_moving_avg_price;
            $cart[$key]['total_price'] = $product->standard_price_moving_avg_price * $value['quantity'];
            $cart[$key]['quantity'] = $value['quantity'];
            $cart[$key]['image'] = $product->product_images[0]->image ?? '';
        }

        return $cart;
    }
}
