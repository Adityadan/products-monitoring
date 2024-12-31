<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_detail';
    protected $guarded = [];

    public function shippingOrder()
    {
        return $this->belongsTo(ShippingOrder::class, 'id_shipping_order');
    }
}
