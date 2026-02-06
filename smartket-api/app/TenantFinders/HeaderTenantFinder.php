<?php

namespace App\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use App\Models\Core\Tenant;

class HeaderTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        // 1. Buscamos un header llamado 'X-Tenant-ID'
        $tenantId = $request->header('X-Tenant-ID');

        if (! $tenantId) {
            return null;
        }

        // 2. Buscamos al tenant por su 'id' (no por 'slug')
        return Tenant::find($tenantId);
    }
}
