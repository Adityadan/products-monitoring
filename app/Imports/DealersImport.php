<?php

namespace App\Imports;

use App\Models\Dealer;
use Illuminate\Support\Collection;
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
        // Lewati dua baris pertama
        $rows->skip(2)->each(function ($row) {
            // Abaikan baris dengan nilai kosong di kolom B
            if ($row[1] === null) {
                return;
            }
            Dealer::updateOrCreate(
                [
                    'kode' => $row[1], // Kolom B
                ],
                [
                    'ahass' => $row[2],  // Kolom C
                    'kota_kab' => $row[3], // Kolom D
                    'kecamatan' => $row[4], // Kolom E
                    'status' => $row[5], // Kolom F
                    'se_area' => $row[6], // Kolom G
                    'group' => $row[7], // Kolom H
                ]
            );
        });
    }
}
