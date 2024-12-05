<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'menus';
    protected $fillable = ['name', 'route', 'parent_id', 'icon', 'color', 'order', 'is_active','permission_name'];

    public function children()
    {
        return $this->hasMany(Menus::class, 'parent_id')->where('is_active', true)->orderBy('order', 'asc');
    }
    public function parent()
    {
        return $this->belongsTo(Menus::class, 'parent_id');
    }
}
