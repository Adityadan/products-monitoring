<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'no' => $row['no'],
            'kode_dealer' => $row['kode_dealer'],
            'kode_ba' => $row['kode_ba'],
            'customer_master_sap' => $row['customer_master_sap'],
            'group_material' => $row['group_material'],
            'group_tobpm' => $row['group_tobpm'],
            'no_part' => $row['no_part'],
            'nama_part' => $row['nama_part'],
            'rank_part' => $row['rank_part'],
            'discontinue' => $row['discontinue'] === 'yes' ? true : false, // Misalnya kolom ini berisi 'yes' atau 'no'
            'kode_gudang' => $row['kode_gudang'],
            'nama_gudang' => $row['nama_gudang'],
            'kode_lokasi' => $row['kode_lokasi'],
            'int' => $row['int'],
            'oh' => $row['oh'],
            'rsv' => $row['rsv'],
            'blk' => $row['blk'],
            'wip' => $row['wip'],
            'bok' => $row['bok'],
            'total_exc_int' => $row['total_exc_int'],
            'stock_days_month' => $row['stock_days_month'],
            'avg_demand_qty' => $row['avg_demand_qty'],
            'avg_demand_amt' => $row['avg_demand_amt'],
            'avg_sales_monthly_qty' => $row['avg_sales_monthly_qty'],
            'avg_sales_monthly_amt' => $row['avg_sales_monthly_amt'],
            'standard_price_moving_avg_price' => $row['standard_price_moving_avg_price'],
            'invt_amt_exc_int' => $row['invt_amt_exc_int'],
        ]);
    }
    public function chunkSize(): int
    {
        return 500;
    }
}
