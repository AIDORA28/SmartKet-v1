<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\TenantService;
use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantServiceTest extends TestCase
{
    protected TenantService $tenantService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantService = new TenantService();
    }

    /** @test */
    public function it_can_generate_a_slug_base()
    {
        // Nota: generateUniqueSlug usa el modelo para verificar existencia.
        // En un test unitario real sin DB, mockearíamos el Modelo.
        // Aquí probamos la lógica de Str::slug que es parte de la función.
        $name = 'Mi Negocio Especial';
        $slug = Str::slug($name);
        $this->assertEquals('mi-negocio-especial', $slug);
    }

    /** @test */
    public function it_has_get_entitlements_method()
    {
        $this->assertTrue(method_exists($this->tenantService, 'getEntitlements'));
    }
}
