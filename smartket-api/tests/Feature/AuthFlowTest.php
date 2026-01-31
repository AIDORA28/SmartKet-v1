<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_token_is_rejected(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalidtoken')->getJson('/api/me');
        $response->assertStatus(401);
    }

    public function test_logout_revokes_token(): void
    {
        $password = 'Password1';
        // Use plaintext; the 'hashed' cast will hash it.
        $user = User::factory()->create(['name' => 'Test User','password' => $password]);

        $login = $this->postJson('/api/login', ['email' => $user->email, 'password' => $password]);
        $login->assertStatus(200);
        $token = $login->json('token');
        $this->assertNotEmpty($token);

        // Access /me succeeds with token
        $meOk = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/me');
        $meOk->assertStatus(200);

        // Logout revokes token
        $logout = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/logout');
        $logout->assertStatus(200);

        // Access /me fails with the same token
        $meFail = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/me');
        $meFail->assertStatus(401);
    }
}
