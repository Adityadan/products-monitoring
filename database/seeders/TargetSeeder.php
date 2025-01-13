<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $target = [
            [
                "id" => 57,
                "kode_customer" => "5200009974",
                "customer_name" => "PT Nusantara Surya Sakti",
                "channel" => "H123",
                "target_part" => 18600000,
                "target_oli" => 29700000,
                "target_app" => 14850000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 58,
                "kode_customer" => "5200019476",
                "customer_name" => "CV Delapan Jaya",
                "channel" => "H123",
                "target_part" => 39950000,
                "target_oli" => 33700000,
                "target_app" => 16850000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 59,
                "kode_customer" => "5200019477",
                "customer_name" => "PT Motor Mega Tano",
                "channel" => "H123",
                "target_part" => 59850000,
                "target_oli" => 69300000,
                "target_app" => 34650000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 60,
                "kode_customer" => "5200019480",
                "customer_name" => "CV Sumber Jaya Abadi",
                "channel" => "H123",
                "target_part" => 81650000,
                "target_oli" => 66600000,
                "target_app" => 33300000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 61,
                "kode_customer" => "5200020001",
                "customer_name" => "PT Nusantara Surya Sakti",
                "channel" => "H123",
                "target_part" => 11500000,
                "target_oli" => 21600000,
                "target_app" => 10800000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 62,
                "kode_customer" => "5200022541",
                "customer_name" => "PT Harapan Utama Makmur",
                "channel" => "H123",
                "target_part" => 120400000,
                "target_oli" => 74700000,
                "target_app" => 37350000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 63,
                "kode_customer" => "5200022542",
                "customer_name" => "PT Harapan Utama Makmur",
                "channel" => "H123",
                "target_part" => 58950000,
                "target_oli" => 53500000,
                "target_app" => 26750000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 64,
                "kode_customer" => "5200022543",
                "customer_name" => "PT Harapan Utama Makmur",
                "channel" => "H123",
                "target_part" => 64600000,
                "target_oli" => 43650000,
                "target_app" => 21825000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 65,
                "kode_customer" => "5200022714",
                "customer_name" => "PT Harapan Utama Makmur",
                "channel" => "H123",
                "target_part" => 32300000,
                "target_oli" => 43750000,
                "target_app" => 21875000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 66,
                "kode_customer" => "5200010034",
                "customer_name" => "Roman Motor",
                "channel" => "H23",
                "target_part" => 45400000,
                "target_oli" => 31600000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 67,
                "kode_customer" => "5200010450",
                "customer_name" => "Wirawan Motor",
                "channel" => "H23",
                "target_part" => 62050000,
                "target_oli" => 29100000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 68,
                "kode_customer" => "5200017940",
                "customer_name" => "Rizki Motor / Tutut Sri Suswati",
                "channel" => "H23",
                "target_part" => 136350000,
                "target_oli" => 51300000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 69,
                "kode_customer" => "5200019573",
                "customer_name" => "Indra Motor / Hendry Pranata",
                "channel" => "H23",
                "target_part" => 100450000,
                "target_oli" => 25950000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 70,
                "kode_customer" => "5200020006",
                "customer_name" => "UD Ahass Citra Jaya Motor /Cokro Un",
                "channel" => "H23",
                "target_part" => 126800000,
                "target_oli" => 39600000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 71,
                "kode_customer" => "5200022411",
                "customer_name" => "Rizki Kilo / Tutut Sri Suswati",
                "channel" => "H23",
                "target_part" => 64950000,
                "target_oli" => 44100000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 72,
                "kode_customer" => "5200022576",
                "customer_name" => "CV Dwi Anugrah Jaya",
                "channel" => "H23",
                "target_part" => 0,
                "target_oli" => 0,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 73,
                "kode_customer" => "5200022726",
                "customer_name" => "PT Harapan Utama Makmur / Harry Sud",
                "channel" => "H23",
                "target_part" => 36700000,
                "target_oli" => 30300000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 74,
                "kode_customer" => "5200023920",
                "customer_name" => "Elvia Jaya / Deasy Tungerapan",
                "channel" => "H23",
                "target_part" => 32300000,
                "target_oli" => 27900000,
                "target_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 75,
                "kode_customer" => "H701",
                "customer_name" => "SO MT HARYONO",
                "channel" => "SO",
                "target_part" => 96350000,
                "target_oli" => 75600000,
                "target_app" => 37800000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 76,
                "kode_customer" => "H702",
                "customer_name" => "SO GROGOT",
                "channel" => "SO",
                "target_part" => 107550000,
                "target_oli" => 64300000,
                "target_app" => 32150000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 77,
                "kode_customer" => "H703",
                "customer_name" => "SO SUDIRMAN",
                "channel" => "SO",
                "target_part" => 43050000,
                "target_oli" => 54850000,
                "target_app" => 27425000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 78,
                "kode_customer" => "H704",
                "customer_name" => "SO PENAJAM",
                "channel" => "SO",
                "target_part" => 36500000,
                "target_oli" => 50100000,
                "target_app" => 25050000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 79,
                "kode_customer" => "H705",
                "customer_name" => "SO KILO",
                "channel" => "SO",
                "target_part" => 55200000,
                "target_oli" => 74700000,
                "target_app" => 37350000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 80,
                "kode_customer" => "H706",
                "customer_name" => "SO SEPINGGAN",
                "channel" => "SO",
                "target_part" => 55200000,
                "target_oli" => 76550000,
                "target_app" => 38275000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 81,
                "kode_customer" => "H707",
                "customer_name" => "SO SIMPANG PAIT",
                "channel" => "SO",
                "target_part" => 52400000,
                "target_oli" => 69950000,
                "target_app" => 34975000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 82,
                "kode_customer" => "H712",
                "customer_name" => "SO TARAKAN",
                "channel" => "SO",
                "target_part" => 92600000,
                "target_oli" => 69950000,
                "target_app" => 34975000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 83,
                "kode_customer" => "H713",
                "customer_name" => "SO BERAU",
                "channel" => "SO",
                "target_part" => 60800000,
                "target_oli" => 86000000,
                "target_app" => 43000000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ],
            [
                "id" => 84,
                "kode_customer" => "H715",
                "customer_name" => "SO TANJUNG SELOR",
                "channel" => "SO",
                "target_part" => 69200000,
                "target_oli" => 60500000,
                "target_app" => 30250000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => null,
                "updated_at" => null,
                "deleted_at" => null
            ]
        ];

        DB::table('target')->insert($target);
    }
}
