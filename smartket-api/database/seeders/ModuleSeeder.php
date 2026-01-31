<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Asiento de Mesero', 'identifier' => 'seat_mesero', 'price_monthly' => 10.00, 'type' => 'seat'],
            ['name' => 'Asiento de Caja', 'identifier' => 'seat_caja', 'price_monthly' => 10.00, 'type' => 'seat'],
            ['name' => 'Módulo de Cocina', 'identifier' => 'module_cocina', 'price_monthly' => 15.00, 'type' => 'module'],
            ['name' => 'Módulo de Delivery', 'identifier' => 'module_delivery', 'price_monthly' => 20.00, 'type' => 'module'],
        ];

        foreach ($modules as $module) {
            Module::firstOrCreate(['identifier' => $module['identifier']], $module);
        }
    }
}
