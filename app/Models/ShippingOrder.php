<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOrder extends Model
{
    //
    protected $table = 'shipping_order';

    protected $fillable = [
        'buyer_dealer',
        'buyer_name',
        'phone',
        'shipping_address',
    ];
}
