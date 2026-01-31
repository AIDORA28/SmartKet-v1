<?php

namespace App\Console\Commands;

use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {nombre_negocio} {email_dueño} {pass_dueño} {--rubro=polleria}';
    protected $description = 'Crea un nuevo tenant utilizando el TenantService.';

    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        parent::__construct();
        $this->tenantService = $tenantService;
    }

    public function handle(): int
    {
        $nombreNegocio = $this->argument('nombre_negocio');
        $emailDueno = $this->argument('email_dueño');
        $passDueno = $this->argument('pass_dueño');
        $rubro = $this->option('rubro');

        $this->info("Iniciando el proceso de creación del tenant para: $emailDueno");

        try {
            $tenant = $this->tenantService->createTenant(
                $nombreNegocio,
                $emailDueno,
                $passDueno,
                $rubro
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
