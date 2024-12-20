<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistanceOrderDealer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'distance_order_dealer';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'dealer_id',
        'kode_dealer',
        'order_distance',
        'area',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'area', 'kota_kab');
        // return $this->belongsTo(Dealer::class);
    }
}
