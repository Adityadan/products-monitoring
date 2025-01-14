<?php

namespace App\Imports;

use App\Models\Dealer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class DealersImport implements ToCollection
{
    /**
     * Proses data Excel sebagai koleksi.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $rows->skip(2)->each(function ($row) {
            $row = $row->toArray();
            // Filter baris untuk menghapus nilai null dan string kosong
            $filteredRow = array_filter($row, function ($value) {
                return $value !== null && $value !== ''; // Jangan hapus nilai 0 atau string kosong
            });

            // Reset indeks array setelah filter agar dimulai dari 0
            $filteredRow = array_values($filteredRow);

            // Jika baris hanya berisi nilai null atau kosong, abaikan
            if (empty($filteredRow)) {
                return;
            }

            // Pengecekan untuk melewati array yang memiliki nilai pertama "total"
            if (isset($filteredRow[0]) && str_contains(strtolower($filteredRow[0]), "total")) {
                return; // Skip array ini jika nilai pertama mengandung "total"
            }

            // Tambahkan nol di depan jika panjang array 0 kurang dari 5 digit
            if (isset($filteredRow[0]) && strlen($filteredRow[0]) < 5) {
                $filteredRow[0] = str_pad($filteredRow[0], 5, '0', STR_PAD_LEFT);
            }
            // Update atau buat record baru di tabel Dealer
            Dealer::updateOrCreate(
                [
                    'kode' => $filteredRow[0], // Kolom B
                ],
                [
                    'kode_customer' => $filteredRow[1],
                    'ahass' => $filteredRow[2],  // Kolom C
                    'kota_kab' => $filteredRow[3], // Kolom D
                    'kecamatan' => $filteredRow[4], // Kolom E
                    'status' => $filteredRow[4], // Kolom F
                    'se_area' => $filteredRow[5], // Kolom G
                    'group' => $filteredRow[6], // Kolom H
                ]
            );
        });
    }
}
