<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Staff;

class TenantController extends Controller
{
    public function show(Request $request)
    {
        $tenantId = $request->header('X-Tenant-ID');
        if (! $tenantId) {
            return response()->json(['message' => 'X-Tenant-ID header is required'], 400);
        }

        $tenant = Tenant::find($tenantId);
        if (! $tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        return response()->json([
            'id' => $tenant->id,
            'nombre_negocio' => $tenant->nombre_negocio,
            'plan' => $tenant->plan,
            'rubro' => $tenant->rubro,
            'setup_complete' => (bool) $tenant->setup_complete,
        ]);
    }

    public function update(Request $request)
    {
        $tenantId = $request->header('X-Tenant-ID');
        if (! $tenantId) {
            return response()->json(['message' => 'X-Tenant-ID header is required'], 400);
        }

        $tenant = Tenant::find($tenantId);
        if (! $tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $data = $request->validate([
            'rubro' => 'required|string|in:minimarket,polleria,restaurante,farmacia',
        ]);

        $tenant->rubro = $data['rubro'];
        $tenant->setup_complete = true; // marcamos el onboarding como completo
        $tenant->save();

        return response()->json([
            'message' => 'Tenant actualizado',
            'tenant' => [
                'id' => $tenant->id,
                'nombre_negocio' => $tenant->nombre_negocio,
                'plan' => $tenant->plan,
                'rubro' => $tenant->rubro,
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

        $tenant->load(['plan', 'modules']);

        $entitlements = [
            'seats' => [],
            'modules' => [],
        ];

        $allModules = \App\Models\Module::all();
        $purchasedModules = $tenant->modules->keyBy('identifier');

        $staffRolesCount = $tenant->execute(function() {
            $counts = [];
            $staffWithRoles = Staff::with('roles')->get();

            foreach ($staffWithRoles as $staffMember) {
                foreach ($staffMember->roles as $role) {
                    if ($role instanceof \App\Models\Role) {
                        if (!isset($counts[$role->name])) {
                            $counts[$role->name] = 0;
                        }
                        $counts[$role->name]++;
                    }
                }
            }
            return collect($counts);
        });

        foreach ($allModules as $module) {
            if ($module->type === 'seat') {
                $identifier = $module->identifier;
                $roleName = str_replace('seat_', '', $identifier);
                $purchasedModule = $purchasedModules->get($identifier);

                $entitlements['seats'][] = [
                    'identifier' => $identifier,
                    'name' => $module->name,
                    'limit' => $purchasedModule ? $purchasedModule->pivot->quantity : 0,
                    'current' => $staffRolesCount->get($roleName, 0),
                ];
            } else {
                $entitlements['modules'][] = [
                    'identifier' => $module->identifier,
                    'name' => $module->name,
                    'enabled' => $purchasedModules->has($module->identifier),
                ];
            }
        }

        return response()->json($entitlements);
    }
}
