<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AuthService;
use App\Models\User;
use App\Models\Tenant;

class AuthServiceTest extends TestCase
{
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /** @test */
    public function it_checks_method_existence()
    {
        $this->assertTrue(method_exists($this->authService, 'getUserProfileData'));
    }
}
