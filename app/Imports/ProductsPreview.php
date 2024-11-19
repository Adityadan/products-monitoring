<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsPreview implements ToArray, WithStartRow
{
    private $previewData = [];

    public function startRow(): int
    {
        return 13; // Mulai dari baris ke-13
    }

    public function array(array $rows)
    {
        foreach ($rows as $row) {
            $filteredRow = array_filter($row, function ($value) {
                return $value !== null && $value !== ''; // Jangan hapus nilai 0 atau string kosong
            });

            $filteredRow = array_values($filteredRow);

            if (empty($filteredRow) || (isset($filteredRow[0]) && strpos(strtolower($filteredRow[0]), "total") !== false)) {
                continue;
            }

            $this->previewData[] = [
                'no' => $filteredRow[0],
                'kode_dealer' => $filteredRow[1],
                'kode_ba' => $filteredRow[2],
                'customer_master_sap' => $filteredRow[3],
                'group_material' => $filteredRow[4],
                'group_tobpm' => $filteredRow[5],
                'no_part' => $filteredRow[6],
                'nama_part' => $filteredRow[7],
                'rank_part' => $filteredRow[8],
                'discontinue' => $filteredRow[9],
                'kode_gudang' => $filteredRow[10],
                'nama_gudang' => $filteredRow[11],
                'kode_lokasi' => $filteredRow[12],
                'int' => $filteredRow[13],
                'oh' => $filteredRow[14],
                'rsv' => $filteredRow[15],
                'blk' => $filteredRow[16],
                'wip' => $filteredRow[17],
                'bok' => $filteredRow[18],
                'total_exc_int' => $filteredRow[19],
                'stock_days_month' => $filteredRow[20],
                'avg_demand_qty' => $filteredRow[21],
                'avg_demand_amt' => $filteredRow[22],
                'avg_sales_monthly_qty' => $filteredRow[23],
                'avg_sales_monthly_amt' => $filteredRow[24],
                'standard_price_moving_avg_price' => $filteredRow[25],
                'invt_amt_exc_int' => $filteredRow[26],
            ];
        }
    }

    public function getPreviewData()
    {
        return $this->previewData;
    }
}
