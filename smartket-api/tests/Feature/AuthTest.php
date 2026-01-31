<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token_on_valid_credentials(): void
    {
        $password = 'Password1';
        // Use plaintext; the 'hashed' cast will hash it.
        $user = User::factory()->create([
            'name' => 'Test User',
            'password' => $password,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',
                     'tenant_id',
                 ]);
    }

    public function test_me_returns_user_data_when_authenticated(): void
    {
        $user = User::factory()->create(['name' => 'Test User','password' => 'Password1']);
        $this->actingAs($user);

        $response = $this->getJson('/api/me');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                 ]);
    }
}
