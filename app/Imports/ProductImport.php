<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
        return 13; // Mulai dari baris ke-13
    }

    public function model(array $row)
    {
        // Filter untuk melewati baris kosong
        $filteredRow = array_filter($row, function ($value) {
            return $value !== null && $value !== ''; // Jangan hapus nilai 0 atau string kosong
        });

        // Reset indeks array setelah filter agar dimulai dari 0
        $filteredRow = array_values($filteredRow);

        // Abaikan baris kosong atau yang memiliki "total" di kolom pertama
        if (empty($filteredRow) || (isset($filteredRow[0]) && strpos(strtolower($filteredRow[0]), "total") !== false)) {
            return null;
        }


        // Logika berdasarkan importType
        if ($this->importType === 'preview') {
            // Tambahkan ke data pratinjau
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
        } elseif ($this->importType === 'import') {
            $insertData = [];
            $updateData = [];
            $timestamp = now(); // To maintain created_at/updated_at consistency if needed

            $existingProduct = Product::where('no_part', $filteredRow[6])
                ->where('kode_dealer', $filteredRow[1])
                ->first();

            if ($existingProduct) {
                // Collect data for bulk update
                $updateData[] = [
                    'id' => $existingProduct->id, // Capture primary key for updates
                    'no' => $filteredRow[0],
                    'kode_dealer' => $filteredRow[1],
                    'kode_ba' => $filteredRow[2],
                    'customer_master_sap' => $filteredRow[3],
                    'group_material' => $filteredRow[4],
                    'group_tobpm' => $filteredRow[5],
                    'nama_part' => $filteredRow[7],
                    'rank_part' => $filteredRow[8],
                    'discontinue' => $filteredRow[9],
                    'kode_gudang' => $filteredRow[10],
                    'nama_gudang' => $filteredRow[11],
                    'kode_lokasi' => $filteredRow[12],
                    'int' => $filteredRow[13],
                    'oh' => $existingProduct->oh + $filteredRow[14], // Increment OH
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
                    'updated_at' => $timestamp,
                ];
            } else {
                // Collect data for bulk insert
                $insertData[] = [
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
                    'oh' => $filteredRow[14], // Initial OH value
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
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            // Step 2: Bulk Insert and Update

            // Perform batch insert for new records
            if (!empty($insertData)) {
                Product::insert($insertData);
            }

            // Perform batch update for existing records
            if (!empty($updateData)) {
                foreach (array_chunk($updateData, 100) as $chunk) {
                    foreach ($chunk as $data) {
                        DB::table('products')
                            ->where('id', $data['id'])
                            ->update($data);
                    }
                }
            }
        }

        return null;
    }

    public function getPreviewData()
    {
        return $this->previewData;
    }
}
