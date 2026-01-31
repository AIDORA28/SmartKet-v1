<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_then_login_returns_token_and_tenantId(): void
    {
        // Skip in non-PgSQL test environments because provisioning uses CREATE DATABASE on pgsql
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'pgsql') {
            $this->markTestSkipped('Provisioning requires pgsql; skipped on non-pgsql driver.');
        }

        // Register a new tenant and owner user
        $payload = [
            'business_name' => 'Mi Negocio de Prueba',
            'email' => 'owner@example.com',
            'password' => 'Password1', // meets regex
            'plan' => 'basic',
            'business_type' => 'polleria',
        ];

        $register = $this->postJson('/api/register', $payload);
        $register->assertStatus(201);

        // Login with the registered credentials
        $login = $this->postJson('/api/login', [
            'email' => $payload['email'],
            'password' => $payload['password'],
        ]);

        $login->assertStatus(200)
            ->assertJsonStructure(['ok', 'token', 'tenant_id', 'tenantId']);

        $body = $login->json();
        $this->assertTrue($body['ok'] === true);
        $this->assertIsString($body['token']);
        // After provisioning, tenantId must be present and numeric
        $this->assertNotNull($body['tenantId']);
        $this->assertIsInt($body['tenantId']);
        $this->assertSame($body['tenantId'], $body['tenant_id']);
    }
}
