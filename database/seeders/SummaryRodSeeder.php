<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SummaryRodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                "kode_customer" => "2100005036",
                "customer_name" => "PT TUNAS DWIPA MATRA",
                "total_amount_part" => 32685746,
                "total_amount_oil" => 23386925,
                "total_amount_app" => 6193820,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "2100005037",
                "customer_name" => "PT TUNAS DWIPA MATRA",
                "total_amount_part" => 26321405,
                "total_amount_oil" => 34766210,
                "total_amount_app" => 5866560,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200009964",
                "customer_name" => "CV DAYA MAKMUR MANDIRI",
                "total_amount_part" => 17990400,
                "total_amount_oil" => 23609155,
                "total_amount_app" => 1410100,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200009972",
                "customer_name" => "PT DAYA ANUGRAH MANDIRI",
                "total_amount_part" => 10695774,
                "total_amount_oil" => 4505590,
                "total_amount_app" => 4253900,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200009973",
                "customer_name" => "PT DAYA ANUGRAH MANDIRI",
                "total_amount_part" => 5096400,
                "total_amount_oil" => 24462520,
                "total_amount_app" => 2152320,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200009974",
                "customer_name" => "PT NUSANTARA SURYA SAKTI",
                "total_amount_part" => 3840942,
                "total_amount_oil" => 6947480,
                "total_amount_app" => 3976200,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200010034",
                "customer_name" => "ROMAN MOTOR",
                "total_amount_part" => 27371897,
                "total_amount_oil" => 24327500,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200010450",
                "customer_name" => "Wirawan Motor",
                "total_amount_part" => 34339020,
                "total_amount_oil" => 19958850,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200017940",
                "customer_name" => "RIZKI MOTOR / TUTUT SRI SUSWATI",
                "total_amount_part" => 41874972,
                "total_amount_oil" => 23852835,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200019476",
                "customer_name" => "CV DELAPAN JAYA",
                "total_amount_part" => 0,
                "total_amount_oil" => 1767005,
                "total_amount_app" => 4947340,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200019477",
                "customer_name" => "PT MOTOR MEGA TANO",
                "total_amount_part" => 24204066,
                "total_amount_oil" => 40364460,
                "total_amount_app" => 8706820,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200019480",
                "customer_name" => "CV SUMBER JAYA ABADI",
                "total_amount_part" => 52660260,
                "total_amount_oil" => 18937515,
                "total_amount_app" => 6066380,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200019573",
                "customer_name" => "INDRA MOTOR / HENDRY PRANATA",
                "total_amount_part" => 81940416,
                "total_amount_oil" => 0,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200020001",
                "customer_name" => "PT NUSANTARA SURYA SAKTI",
                "total_amount_part" => 4323330,
                "total_amount_oil" => 1762600,
                "total_amount_app" => 4362650,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200020006",
                "customer_name" => "UD AHASS CITRA JAYA MOTOR /COKRO UN",
                "total_amount_part" => 80160570,
                "total_amount_oil" => 27531240,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200022411",
                "customer_name" => "RIZKI KILO / TUTUT SRI SUSWATI",
                "total_amount_part" => 46461614,
                "total_amount_oil" => 21544540,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200022541",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 96876805,
                "total_amount_oil" => 49905460,
                "total_amount_app" => 2908700,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200022542",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 36543710,
                "total_amount_oil" => 29769605,
                "total_amount_app" => 218300,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200022543",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 23165400,
                "total_amount_oil" => 37369270,
                "total_amount_app" => 3913910,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200022714",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 36307490,
                "total_amount_oil" => 37399065,
                "total_amount_app" => 2706920,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200022726",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR / HARRY SUD",
                "total_amount_part" => 21442460,
                "total_amount_oil" => 18620560,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "5200023920",
                "customer_name" => "ELVIA JAYA / DEASY TUNGERAPAN",
                "total_amount_part" => 9061520,
                "total_amount_oil" => 11600480,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "IC00000004",
                "customer_name" => "INTERNAL MEMO - ADVERTISING & PROMO",
                "total_amount_part" => 0,
                "total_amount_oil" => 0,
                "total_amount_app" => 200600,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "IC00000011",
                "customer_name" => "INTERNAL MEMO - Campaign Free Gift",
                "total_amount_part" => 23680740,
                "total_amount_oil" => 27256500,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "IC00000013",
                "customer_name" => "INTERNAL MEMO - FREE GIFT",
                "total_amount_part" => 0,
                "total_amount_oil" => 0,
                "total_amount_app" => 27140000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:21",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => "2025/1/13 08:14:34"
            ],
            [
                "kode_customer" => "2100005036",
                "customer_name" => "PT TUNAS DWIPA MATRA",
                "total_amount_part" => 32685746,
                "total_amount_oil" => 23386925,
                "total_amount_app" => 6193820,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "2100005037",
                "customer_name" => "PT TUNAS DWIPA MATRA",
                "total_amount_part" => 26321405,
                "total_amount_oil" => 34766210,
                "total_amount_app" => 5866560,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200009964",
                "customer_name" => "CV DAYA MAKMUR MANDIRI",
                "total_amount_part" => 17990400,
                "total_amount_oil" => 23609155,
                "total_amount_app" => 1410100,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200009972",
                "customer_name" => "PT DAYA ANUGRAH MANDIRI",
                "total_amount_part" => 10695774,
                "total_amount_oil" => 4505590,
                "total_amount_app" => 4253900,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200009973",
                "customer_name" => "PT DAYA ANUGRAH MANDIRI",
                "total_amount_part" => 5096400,
                "total_amount_oil" => 24462520,
                "total_amount_app" => 2152320,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200009974",
                "customer_name" => "PT NUSANTARA SURYA SAKTI",
                "total_amount_part" => 3840942,
                "total_amount_oil" => 6947480,
                "total_amount_app" => 3976200,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200010034",
                "customer_name" => "ROMAN MOTOR",
                "total_amount_part" => 27371897,
                "total_amount_oil" => 24327500,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200010450",
                "customer_name" => "Wirawan Motor",
                "total_amount_part" => 34339020,
                "total_amount_oil" => 19958850,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200017940",
                "customer_name" => "RIZKI MOTOR / TUTUT SRI SUSWATI",
                "total_amount_part" => 41874972,
                "total_amount_oil" => 23852835,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200019476",
                "customer_name" => "CV DELAPAN JAYA",
                "total_amount_part" => 0,
                "total_amount_oil" => 1767005,
                "total_amount_app" => 4947340,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200019477",
                "customer_name" => "PT MOTOR MEGA TANO",
                "total_amount_part" => 24204066,
                "total_amount_oil" => 40364460,
                "total_amount_app" => 8706820,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200019480",
                "customer_name" => "CV SUMBER JAYA ABADI",
                "total_amount_part" => 52660260,
                "total_amount_oil" => 18937515,
                "total_amount_app" => 6066380,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200019573",
                "customer_name" => "INDRA MOTOR / HENDRY PRANATA",
                "total_amount_part" => 81940416,
                "total_amount_oil" => 0,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200020001",
                "customer_name" => "PT NUSANTARA SURYA SAKTI",
                "total_amount_part" => 4323330,
                "total_amount_oil" => 1762600,
                "total_amount_app" => 4362650,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200020006",
                "customer_name" => "UD AHASS CITRA JAYA MOTOR /COKRO UN",
                "total_amount_part" => 80160570,
                "total_amount_oil" => 27531240,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200022411",
                "customer_name" => "RIZKI KILO / TUTUT SRI SUSWATI",
                "total_amount_part" => 46461614,
                "total_amount_oil" => 21544540,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200022541",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 96876805,
                "total_amount_oil" => 49905460,
                "total_amount_app" => 2908700,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200022542",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 36543710,
                "total_amount_oil" => 29769605,
                "total_amount_app" => 218300,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200022543",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 23165400,
                "total_amount_oil" => 37369270,
                "total_amount_app" => 3913910,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200022714",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR",
                "total_amount_part" => 36307490,
                "total_amount_oil" => 37399065,
                "total_amount_app" => 2706920,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200022726",
                "customer_name" => "PT HARAPAN UTAMA MAKMUR / HARRY SUD",
                "total_amount_part" => 21442460,
                "total_amount_oil" => 18620560,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "5200023920",
                "customer_name" => "ELVIA JAYA / DEASY TUNGERAPAN",
                "total_amount_part" => 9061520,
                "total_amount_oil" => 11600480,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H701",
                "customer_name" => "SO MT HARYONO",
                "total_amount_part" => 72834863,
                "total_amount_oil" => 76351320,
                "total_amount_app" => 9344850,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H702",
                "customer_name" => "SO GROGOT",
                "total_amount_part" => 67119325,
                "total_amount_oil" => 206307480,
                "total_amount_app" => 5775080,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H703",
                "customer_name" => "SO SUDIRMAN",
                "total_amount_part" => 15580105,
                "total_amount_oil" => 26816640,
                "total_amount_app" => 3910520,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H704",
                "customer_name" => "SO PENAJAM",
                "total_amount_part" => 12608150,
                "total_amount_oil" => 28226400,
                "total_amount_app" => 3093960,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H705",
                "customer_name" => "SO KILO",
                "total_amount_part" => 35645054,
                "total_amount_oil" => 72345780,
                "total_amount_app" => 6187680,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H706",
                "customer_name" => "SO SEPINGGAN",
                "total_amount_part" => 18829810,
                "total_amount_oil" => 51306000,
                "total_amount_app" => 5179020,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H707",
                "customer_name" => "SO SIMPANG PAIT",
                "total_amount_part" => 23508479,
                "total_amount_oil" => 24563020,
                "total_amount_app" => 2253210,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H712",
                "customer_name" => "SO TARAKAN",
                "total_amount_part" => 69326217,
                "total_amount_oil" => 98088780,
                "total_amount_app" => 5422540,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H713",
                "customer_name" => "SO BERAU",
                "total_amount_part" => 31996310,
                "total_amount_oil" => 21322455,
                "total_amount_app" => 6283100,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "H715",
                "customer_name" => "SO TANJUNG SELOR",
                "total_amount_part" => 117556310,
                "total_amount_oil" => 64060305,
                "total_amount_app" => 19036960,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "IC00000004",
                "customer_name" => "INTERNAL MEMO - ADVERTISING & PROMO",
                "total_amount_part" => 0,
                "total_amount_oil" => 0,
                "total_amount_app" => 200600,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "IC00000011",
                "customer_name" => "INTERNAL MEMO - Campaign Free Gift",
                "total_amount_part" => 23680740,
                "total_amount_oil" => 27256500,
                "total_amount_app" => 0,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ],
            [
                "kode_customer" => "IC00000013",
                "customer_name" => "INTERNAL MEMO - FREE GIFT",
                "total_amount_part" => 0,
                "total_amount_oil" => 0,
                "total_amount_app" => 27140000,
                "periode" => "2025/1/1 00:00:00",
                "created_at" => "2025/1/13 08:14:34",
                "updated_at" => "2025/1/13 08:14:34",
                "deleted_at" => null
            ]
        ];

        DB::table('summary_rod')->insert($data);
    }
}
