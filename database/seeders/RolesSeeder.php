<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'superadmin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'main_dealer',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sub_dealer',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $model_has_roles = [
            ["role_id" => 2, "model_type" => "App\\Models\\User", "model_id" => 2],
            ["role_id" => 3, "model_type" => "App\\Models\\User", "model_id" => 4],
            ["role_id" => 3, "model_type" => "App\\Models\\User", "model_id" => 5],
            ["role_id" => 1, "model_type" => "App\\Models\\User", "model_id" => 1],
        ];

        DB::table('model_has_roles')->insert($model_has_roles);
    }
}
