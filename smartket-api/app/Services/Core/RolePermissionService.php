<?php

namespace App\Services\Core;

use App\Models\Core\Permission;
use App\Models\Core\Role;
use Illuminate\Support\Facades\Log;

class RolePermissionService
{
    /**
     * Initial permissions catalog.
     */
    public function getPermissionsCatalog(): array
    {
        return [
            ['name' => 'products.view', 'display_name' => 'Ver Productos', 'group_name' => 'Inventario'],
            ['name' => 'products.manage', 'display_name' => 'Gestionar Productos', 'group_name' => 'Inventario'],
            ['name' => 'inventory.stock', 'display_name' => 'Gestionar Stock', 'group_name' => 'Inventario'],
            
            ['name' => 'sales.view', 'display_name' => 'Ver Ventas', 'group_name' => 'Ventas'],
            ['name' => 'sales.create', 'display_name' => 'Realizar Ventas', 'group_name' => 'Ventas'],
            ['name' => 'cash.manage', 'display_name' => 'Gestionar Caja', 'group_name' => 'Ventas'],
            
            ['name' => 'orders.manage', 'display_name' => 'Gestionar Pedidos', 'group_name' => 'Operaciones'],
            ['name' => 'kitchen.view', 'display_name' => 'Ver Comandas de Cocina', 'group_name' => 'Operaciones'],
            
            ['name' => 'staff.manage', 'display_name' => 'Gestionar Personal', 'group_name' => 'Administración'],
            ['name' => 'branches.manage', 'display_name' => 'Gestionar Sucursales', 'group_name' => 'Administración'],
            ['name' => 'reports.view', 'display_name' => 'Ver Reportes y Comparativas', 'group_name' => 'Administración'],
        ];
    }

    /**
     * Provision roles and permissions based on business type (vertical).
     */
    public function provisionForVertical(string $vertical): void
    {
        Log::info("RolePermissionService: Provisioning for vertical '$vertical'");

        // 1. Create all permissions first
        foreach ($this->getPermissionsCatalog() as $permData) {
            Permission::firstOrCreate(['name' => $permData['name']], $permData);
        }

        // 2. Create roles based on vertical
        $templates = $this->getTemplatesByVertical($vertical);

        foreach ($templates as $roleName => $perms) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['description' => "Rol predeterminado para $roleName", 'vertical' => $vertical]
            );

            $permissionIds = Permission::whereIn('name', $perms)->pluck('id');
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }
    }

    private function getTemplatesByVertical(string $vertical): array
    {
        $commonRoles = [
            'Administrador' => ['products.manage', 'inventory.stock', 'sales.view', 'sales.create', 'cash.manage', 'staff.manage', 'branches.manage', 'reports.view'],
            'Vendedor' => ['products.view', 'sales.create', 'cash.manage'],
        ];

        $verticalRoles = match ($vertical) {
            'polleria', 'restaurante', 'horeca' => [
                'Mesero' => ['products.view', 'orders.manage'],
                'Cocinero' => ['kitchen.view'],
                'Cajero' => ['sales.create', 'cash.manage', 'sales.view'],
            ],
            'retail', 'minimarket' => [
                'Almacenero' => ['products.view', 'inventory.stock'],
                'Supervisor' => ['products.manage', 'inventory.stock', 'sales.view', 'reports.view'],
            ],
            default => [],
        };

        return array_merge($commonRoles, $verticalRoles);
    }
}
