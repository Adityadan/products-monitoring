<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Product A',
                'description' => 'Description for Product A',
                'price' => 100.00,
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Product B',
                'description' => 'Description for Product B',
                'price' => 150.00,
                'quantity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Product C',
                'description' => 'Description for Product C',
                'price' => 200.00,
                'quantity' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
