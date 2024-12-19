<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_image';

    protected $guarded = ['id'];

    protected $fillable = [
        'no_part',
        'image',
        'created_by',
        'updated_by',
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'no_part', 'no_part');
    }
}
