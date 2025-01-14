<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class, 'kode_dealer', 'kode');
    }

    public function distance_order()
    {
        return $this->hasMany(DistanceOrderDealer::class, 'area', 'kota_kab');
        // return $this->hasMany(DistanceOrderDealer::class);
    }
}
