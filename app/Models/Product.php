<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no',
        'kode_dealer',
        'kode_ba',
        'customer_master_sap',
        'group_material',
        'group_tobpm',
        'no_part',
        'nama_part',
        'rank_part',
        'discontinue',
        'kode_gudang',
        'nama_gudang',
        'kode_lokasi',
        'int',
        'oh',
        'rsv',
        'blk',
        'wip',
        'bok',
        'total_exc_int',
        'stock_days_month',
        'avg_demand_qty',
        'avg_demand_amt',
        'avg_sales_monthly_qty',
        'avg_sales_monthly_amt',
        'standard_price_moving_avg_price',
        'invt_amt_exc_int',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'kode_dealer', 'kode');
    }

}
