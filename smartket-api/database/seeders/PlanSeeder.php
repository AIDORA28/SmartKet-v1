<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::firstOrCreate(
            ['name' => 'Trial'],
            [
                'price_monthly' => 0,
                'is_trial' => true,
                'features' => [
                    'max_total_users' => 2, // 1 Dueño + 1 Empleado
                    'max_branches' => 1,
                ],
            ]
        );

        Plan::firstOrCreate(
            ['name' => 'Pro'],
            [
                'price_monthly' => 99.99,
                'is_trial' => false,
                'features' => [
                    'max_total_users' => 5,
                    'max_branches' => 2,
                ],
            ]
        );
    }
}
