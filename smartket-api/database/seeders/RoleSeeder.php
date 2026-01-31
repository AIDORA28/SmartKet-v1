<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrador'],
            ['name' => 'mesero', 'display_name' => 'Mesero'],
            ['name' => 'cocina', 'display_name' => 'Cocina'],
            ['name' => 'caja', 'display_name' => 'Cajero'],
            ['name' => 'delivery', 'display_name' => 'Repartidor'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
