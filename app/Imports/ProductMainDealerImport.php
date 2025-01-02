<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductMainDealerImport implements ToModel, WithStartRow, WithChunkReading
{
    protected $importType;

    public function __construct($importType = '')
    {
        $this->importType = $importType;
    }

    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua
    }

    public function chunkSize(): int
    {
        return 1000; // Ukuran chunk 1000 baris
    }

    public function model(array $row)
    {
        // Filter untuk melewati baris kosong
        $filteredRow = array_filter($row, fn($value) => $value !== null && $value !== '');

        // Reset indeks array setelah filter agar dimulai dari 0
        $filteredRow = array_values($filteredRow);

        // Tambahkan nilai default untuk menghindari error index out of bounds
        $filteredRow += array_fill(0, 7, null);

        try {
            $this->handleImport($filteredRow);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Error processing row: ' . json_encode($row) . ' - ' . $e->getMessage());
        }

        return null;
    }

    private function handleImport(array $row)
    {
        $row[1] = str_replace('-', '', $row[1]);
        $timestamp = now();
        $kodeDealer = Auth::user()->kode_dealer;

        // Cek apakah produk sudah ada di database
        $existingProduct = Product::where('no_part', $row[1])
            ->where('kode_dealer', $kodeDealer)
            ->first();

        if ($existingProduct) {
            // Jika ada, update data
            $existingProduct->update([
                'no' => $row[0] ?? null,
                'nama_part' => $row[2] ?? null,
                'oh' => $row[4] ?? null,
                'standard_price_moving_avg_price' => $row[5] ?? null,
                'updated_at' => $timestamp,
            ]);
        } else {
            // Jika tidak ada, insert data baru
            Product::create([
                'no' => $row[0] ?? null,
                'kode_dealer' => $kodeDealer,
                'no_part' => $row[1] ?? null,
                'nama_part' => $row[2] ?? null,
                'oh' => $row[4] ?? null,
                'standard_price_moving_avg_price' => $row[5] ?? null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
