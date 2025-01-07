<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SummaryRod extends Model
{
    use  SoftDeletes;
    protected $table = 'summary_rod';
    protected $guarded = [];
}
