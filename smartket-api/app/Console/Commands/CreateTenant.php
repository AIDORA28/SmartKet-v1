<?php

namespace App\Console\Commands;

use App\Services\Core\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {business_name} {email_owner} {pass_owner} {--business_type=polleria}';
    protected $description = 'Crea un nuevo tenant utilizando el TenantService.';

    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        parent::__construct();
        $this->tenantService = $tenantService;
    }

    public function handle(): int
    {
        $businessName = $this->argument('business_name');
        $emailOwner = $this->argument('email_owner');
        $passOwner = $this->argument('pass_owner');
        $businessType = $this->option('business_type');

        $this->info("Iniciando el proceso de creación del tenant para: $emailDueno");

        try {
            $tenant = $this->tenantService->createTenant(
                $businessName,
                $emailOwner,
                $passOwner,
                $businessType
            );

            $this->info('¡Negocio creado con éxito!');
            $this->line("  - Tenant ID: <fg=yellow>{$tenant->id}</>");
            $this->line("  - Slug: <fg=yellow>{$tenant->slug}</>");
            $this->line("  - DB Name: <fg=yellow>{$tenant->db_name}</>");

            return self::SUCCESS;

        } catch (\Exception $e) {
            Log::error("CreateTenant Command: El servicio falló. Error: " . $e->getMessage());
            $this->error('Ocurrió un error al crear el negocio:');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
