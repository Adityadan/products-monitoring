<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToModel, WithStartRow
{

    private $previewData = [];
    protected $importType;

    public function __construct($importType = '')
    {
        $this->importType = $importType;
    }
    public function startRow(): int
    {
        return 13; // Mulai dari baris ke-3
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        dd($row, $this->importType);
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
        // dd($filteredRow[0], $filteredRow[1], $filteredRow[2],$filteredRow);
        $this->previewData[] = [
            'no' => $filteredRow[0] ?? null,
            'kode_dealer' => $filteredRow[1] ?? null,
            'kode_ba' => $filteredRow[2] ?? null,
            'customer_master_sap' => $filteredRow[3] ?? null,
            'group_material' => $filteredRow[4] ?? null,
            'group_tobpm' => $filteredRow[5] ?? null,
            'no_part' => $filteredRow[6] ?? null,
            'nama_part' => $filteredRow[7] ?? null,
            'rank_part' => $filteredRow[8] ?? null,
            'discontinue' => $filteredRow[9] ?? null,
            'kode_gudang' => $filteredRow[10] ?? null,
            'nama_gudang' => $filteredRow[11] ?? null,
            'kode_lokasi' => $filteredRow[12] ?? null,
            'int' => $filteredRow[13] ?? null,
            'oh' => $filteredRow[14] ?? null,
            'rsv' => $filteredRow[15] ?? null,
            'blk' => $filteredRow[16] ?? null,
            'wip' => $filteredRow[17] ?? null,
            'bok' => $filteredRow[18] ?? null,
            'total_exc_int' => $filteredRow[19] ?? null,
            'stock_days_month' => $filteredRow[20] ?? null,
            'avg_demand_qty' => $filteredRow[21] ?? null,
            'avg_demand_amt' => $filteredRow[22] ?? null,
            'avg_sales_monthly_qty' => $filteredRow[23] ?? null,
            'avg_sales_monthly_amt' => $filteredRow[24] ?? null,
            'standard_price_moving_avg_price' => $filteredRow[25] ?? null,
            'invt_amt_exc_int' => $filteredRow[26] ?? null,
        ];
        $row = $filteredRow;
        // return new Product([
        //     'no' => $row[0],
        //     'kode_dealer' => $row[1],
        //     'kode_ba' => $row[2],
        //     'customer_master_sap' => $row[3],
        //     'group_material' => $row[4],
        //     'group_tobpm' => $row[5],
        //     'no_part' => $row[6],
        //     'nama_part' => $row[7],
        //     'rank_part' => $row[8],
        //     'discontinue' => $row[9],
        //     'kode_gudang' => $row[10],
        //     'nama_gudang' => $row[11],
        //     'kode_lokasi' => $row[12],
        //     'int' => $row[13],
        //     'oh' => $row[14],
        //     'rsv' => $row[15],
        //     'blk' => $row[16],
        //     'wip' => $row[17],
        //     'bok' => $row[18],
        //     'total_exc_int' => $row[19],
        //     'stock_days_month' => $row[20],
        //     'avg_demand_qty' => $row[21],
        //     'avg_demand_amt' => $row[22],
        //     'avg_sales_monthly_qty' => $row[23],
        //     'avg_sales_monthly_amt' => $row[24],
        //     'standard_price_moving_avg_price' => $row[25],
        //     'invt_amt_exc_int' => $row[26],
        // ]);
    }

    public function getPreviewData(){
        return $this->previewData;
    }
}
