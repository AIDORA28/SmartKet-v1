<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\TenantSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders de la base de datos principal (landlord)
        $this->call(\Database\Seeders\PlanSeeder::class);
        $this->call(\Database\Seeders\RoleSeeder::class);
        $this->call(\Database\Seeders\ModuleSeeder::class);
    }
}
