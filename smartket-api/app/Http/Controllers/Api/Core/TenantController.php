<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Tenant;
use App\Models\Core\Staff;

class TenantController extends Controller
{
    protected \App\Services\Core\TenantService $tenantService;

    public function __construct(\App\Services\Core\TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function show(Request $request)
    {
        $tenant = Tenant::current();

        if (!$tenant && $request->hasHeader('X-Tenant-ID')) {
            $tenant = Tenant::find($request->header('X-Tenant-ID'));
        }

        if (! $tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        return response()->json([
            'id' => $tenant->id,
            'business_name' => $tenant->business_name,
            'plan' => $tenant->plan,
            'business_type' => $tenant->business_type,
            'setup_complete' => (bool) $tenant->setup_complete,
        ]);
    }

    public function update(Request $request)
    {
        $tenant = Tenant::current();

        if (!$tenant && $request->hasHeader('X-Tenant-ID')) {
            $tenant = Tenant::find($request->header('X-Tenant-ID'));
        }

        if (! $tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $data = $request->validate([
            'business_type' => 'required|string|in:minimarket,polleria,restaurante,farmacia',
        ]);

        $tenant->business_type = $data['business_type'];
        $tenant->setup_complete = true; // marcamos el onboarding como completo
        $tenant->save();

        return response()->json([
            'message' => 'Tenant actualizado',
            'tenant' => [
                'id' => $tenant->id,
                'business_name' => $tenant->business_name,
                'plan' => $tenant->plan,
                'business_type' => $tenant->business_type,
                'setup_complete' => (bool) $tenant->setup_complete,
            ],
        ]);
    }

    public function getEntitlements(Request $request)
    {
        $tenant = Tenant::current();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $entitlements = $this->tenantService->getEntitlements($tenant);

        return response()->json($entitlements);
    }
}
