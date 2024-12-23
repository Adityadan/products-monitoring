<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expeditions extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'expeditions';

    protected $fillable = ['name', 'code', 'logo', 'contact_number', 'email', 'website', 'address', 'is_active'];

    public $timestamps = true;


}
