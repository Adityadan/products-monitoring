<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Mulai dari baris ke-3
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Filter untuk melewati baris kosong
        $filteredRow = array_filter($row, function ($value) {
            return $value !== null && $value !== ''; // Jangan hapus nilai 0 atau string kosong
        });

        // Reset indeks array setelah filter agar dimulai dari 0
        $filteredRow = array_values($filteredRow);

        // Jika baris hanya berisi nilai null atau kosong, abaikan
        if (empty($filteredRow)) {
            return null;
        }

        // Pengecekan untuk melewati array yang memiliki nilai pertama "total"
        if (isset($filteredRow[0]) && strpos(strtolower($filteredRow[0]), "total") !== false) {
            return null; // Skip array ini jika nilai pertama mengandung "total"
        }

        // Debugging untuk melihat nilai-nilai yang ada setelah filter
        // dd($filteredRow[0], $filteredRow[1], $filteredRow[2]);

        $row = $filteredRow;
        return new Product([
            'no' => $row[0],
            'kode_dealer' => $row[1],
            'kode_ba' => $row[2],
            'customer_master_sap' => $row[3],
            'group_material' => $row[4],
            'group_tobpm' => $row[5],
            'no_part' => $row[6],
            'nama_part' => $row[7],
            'rank_part' => $row[8],
            'discontinue' => $row[9],
            'kode_gudang' => $row[10],
            'nama_gudang' => $row[11],
            'kode_lokasi' => $row[12],
            'int' => $row[13],
            'oh' => $row[14],
            'rsv' => $row[15],
            'blk' => $row[16],
            'wip' => $row[17],
            'bok' => $row[18],
            'total_exc_int' => $row[19],
            'stock_days_month' => $row[20],
            'avg_demand_qty' => $row[21],
            'avg_demand_amt' => $row[22],
            'avg_sales_monthly_qty' => $row[23],
            'avg_sales_monthly_amt' => $row[24],
            'standard_price_moving_avg_price' => $row[25],
            'invt_amt_exc_int' => $row[26],
        ]);
    }
}
