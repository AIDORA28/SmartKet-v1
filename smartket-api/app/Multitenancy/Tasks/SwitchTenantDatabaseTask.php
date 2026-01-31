<?php

namespace App\Multitenancy\Tasks;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Contracts\IsTenant; // <-- CAMBIO 1: Importar el "Contrato"
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchTenantDatabaseTask implements SwitchTenantTask
{
    protected array $originalConfig = [];

    public function makeCurrent(IsTenant $tenant): void // <-- CAMBIO 2: Usar el "Contrato"
    {
        $this->originalConfig = Config::get('database.connections.tenant');

        Config::set('database.connections.tenant.database', $tenant->db_name);
        Config::set('database.connections.tenant.username', $tenant->db_user);
        Config::set('database.connections.tenant.password', decrypt($tenant->db_password));
    }

    public function forgetCurrent(): void
    {
        foreach ($this->originalConfig as $key => $value) {
            Config::set('database.connections.tenant.' . $key, $value);
        }
    }
}