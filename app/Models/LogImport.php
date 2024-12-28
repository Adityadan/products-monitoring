<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogImport extends Model
{
    protected $table = 'log_imports';
    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'status',
        'message',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
