<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expeditions = [
            ['name' => 'J&T Express', 'code' => 'jnt', 'is_active' => true],
            ['name' => 'JNE', 'code' => 'jne', 'is_active' => true],
            ['name' => 'SiCepat', 'code' => 'sicepat', 'is_active' => true],
            ['name' => 'Ninja Xpress', 'code' => 'ninja', 'is_active' => true],
            ['name' => 'TIKI', 'code' => 'tiki', 'is_active' => true],
            ['name' => 'Pos Indonesia', 'code' => 'pos', 'is_active' => true],
            ['name' => 'Shopee Xpress', 'code' => 'shopee', 'is_active' => true],
            ['name' => 'Wahana', 'code' => 'wahana', 'is_active' => true],
            ['name' => 'Lion Parcel', 'code' => 'lion', 'is_active' => true],
            ['name' => 'RPX (Rapid Express)', 'code' => 'rpx', 'is_active' => true],
            ['name' => 'LBC Express', 'code' => 'lbc', 'is_active' => true],
            ['name' => 'Anteraja', 'code' => 'anteraja', 'is_active' => true],
            ['name' => 'GrabExpress', 'code' => 'grab', 'is_active' => true],
            ['name' => 'GoSend', 'code' => 'gosend', 'is_active' => true],
            ['name' => 'Paxel', 'code' => 'paxel', 'is_active' => true],
            ['name' => 'SAP Express', 'code' => 'sap', 'is_active' => true],
        ];

        DB::table('expeditions')->insert($expeditions);
    }
}
