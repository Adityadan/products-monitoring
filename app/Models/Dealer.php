<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'kode',
        'ahass',
        'kota_kab',
        'kecamatan',
        'status',
        'se_area',
        'group',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'kode_dealer', 'kode');
    }
}
