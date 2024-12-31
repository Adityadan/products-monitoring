<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DealerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dealers')->insert([
            'kode' => '00000',
            'ahass' => 'Main Dealer',
            'kota_kab' => 'BALIKPAPAN',
            'kecamatan' => 'BALIKPAPAN SELATAN',
            'status' => 'MAINDEALER',
            'se_area' => 'ADMIN',
            'group' => 'A',
        ]);

        DB::table('dealers')->insert([
            'kode' => '00576',
            'ahass' => 'PT. HARAPAN UTAMA MAKMUR - KARANGJATI',
            'kota_kab' => 'BALIKPAPAN',
            'kecamatan' => 'BALIKPAPAN TENGAH',
            'status' => 'H123',
            'se_area' => 'Alfaj',
            'group' => 'HU',
        ]);

        DB::table('dealers')->insert([
            'kode' => '00762',
            'ahass' => 'UD. CITRA JAYA MOTOR',
            'kota_kab' => 'BERAU',
            'kecamatan' => 'TANJUNG REDEB',
            'status' => 'H23',
            'se_area' => 'Daniel',
            'group' => 'H23',
        ]);
    }
}
