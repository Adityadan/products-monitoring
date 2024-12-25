<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Nama tabel di database
    protected $table = 'orders';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'no_part',
        'kode_dealer',
        'product_name',
        'quantity',
        'price',
        'subtotal',
        'total_price',
        'total_items',
        'notes',
        'id_shipping_order',
        'no_resi',
        'id_expedition',
    ];
}
