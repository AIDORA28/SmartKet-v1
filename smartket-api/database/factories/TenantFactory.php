<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        return [
            'business_name' => $name,
            'slug' => Str::slug($name),
            'business_type' => $this->faker->randomElement(['polleria', 'minimarket', 'restaurante']),
            'db_name' => 'smartket_test_' . Str::random(4),
            'db_user' => 'user_test',
            'db_password' => encrypt('secret'),
            'setup_complete' => true,
        ];
    }
}
