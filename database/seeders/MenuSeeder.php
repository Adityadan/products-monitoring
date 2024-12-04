<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard.index',
                'parent_id' => null,
                'icon' => 'fas fa-chart-pie',
                'color' => null,
                'order' => 1,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Products',
                'route' => null,
                'parent_id' => null,
                'icon' => 'fas fa-user-cog',
                'color' => null,
                'order' => 2,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'List Products',
                'route' => 'product.index',
                'parent_id' => 2,
                'icon' => null,
                'color' => null,
                'order' => 1,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dealer Products',
                'route' => 'dealer-product.index',
                'parent_id' => 2,
                'icon' => null,
                'color' => null,
                'order' => 2,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'List Dealer',
                'route' => 'dealer.index',
                'parent_id' => null,
                'icon' => 'fas fa-solid fa-store',
                'color' => null,
                'order' => 3,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Management User',
                'route' => 'users.index',
                'parent_id' => null,
                'icon' => 'fas fa-users',
                'color' => null,
                'order' => 4,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roles',
                'route' => null,
                'parent_id' => null,
                'icon' => 'fas fa-user-cog',
                'color' => null,
                'order' => 5,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Master Roles',
                'route' => 'roles.index',
                'parent_id' => 7,
                'icon' => null,
                'color' => null,
                'order' => 1,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Assign Roles to User',
                'route' => 'roles.assign',
                'parent_id' => 7,
                'icon' => null,
                'color' => null,
                'order' => 2,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Management Permission',
                'route' => 'permissions.index',
                'parent_id' => 13,
                'icon' => null,
                'color' => null,
                'order' => 6,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Menu Management',
                'route' => 'menus.index',
                'parent_id' => null,
                'icon' => 'fas fa-list',
                'color' => null,
                'order' => null,
                'is_active' => 't',
                'created_at' => '2024-12-03 14:14:46',
                'updated_at' => '2024-12-03 14:43:19',
            ],
            [
                'name' => 'Assign Permission To Roles',
                'route' => 'permissions.assign',
                'parent_id' => 13,
                'icon' => null,
                'color' => null,
                'order' => 7,
                'is_active' => 't',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Permission',
                'route' => null,
                'parent_id' => null,
                'icon' => 'fas fa-lock',
                'color' => null,
                'order' => null,
                'is_active' => 't',
                'created_at' => '2024-12-03 14:14:46',
                'updated_at' => '2024-12-03 14:43:19',
            ],
        ];

        DB::table('menus')->insert($menus);
    }
}
