<?php

namespace App\Services\Core;

use App\Models\Core\Example;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceCreated;

/**
 * ✅ EJEMPLO DE SERVICE CORRECTO
 * 
 * Responsabilidades:
 * 1. Contener toda la lógica de negocio
 * 2. Orquestar operaciones entre modelos
 * 3. Manejar transacciones
 * 4. Disparar eventos/notifications
 */
class ExampleService
{
    /**
     * Listar recursos con paginación
     */
    public function list(int $perPage = 15)
    {
        return Example::with('category')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Buscar recurso por ID
     */
    public function find(int $id): ?Example
    {
        return Example::with('category')->find($id);
    }

    /**
     * ✅ Crear recurso con lógica de negocio compleja
     */
    public function create(array $data): Example
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear recurso principal
            $resource = Example::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'status' => 'pending',
            ]);

            // 2. Ejecutar lógica de negocio post-creación
            $this->processInitialSetup($resource);

            // 3. Notificar stakeholders
            $this->notifyCreation($resource);

            // 4. Registrar auditoría
            $this->logAudit('resource.created', $resource);

            return $resource->fresh(['category']);
        });
    }

    /**
     * ✅ Actualizar con validación de negocio
     */
    public function update(int $id, array $data): Example
    {
        $resource = Example::findOrFail($id);

        return DB::transaction(function () use ($resource, $data) {
            // Validar reglas de negocio
            if (isset($data['category_id']) && $data['category_id'] !== $resource->category_id) {
                $this->validateCategoryChange($resource, $data['category_id']);
            }

            $resource->update($data);

            return $resource->fresh();
        });
    }

    /**
     * ✅ Eliminación con limpieza de dependencias
     */
    public function delete(int $id): bool
    {
        $resource = Example::findOrFail($id);

        return DB::transaction(function () use ($resource) {
            // Limpiar dependencias
            $resource->dependencies()->delete();
            
            // Soft delete del recurso
            return $resource->delete();
        });
    }

    /**
     * ✅ Acción de negocio específica
     */
    public function activate(int $id): Example
    {
        $resource = Example::findOrFail($id);

        if ($resource->status === 'active') {
            throw new \DomainException('Resource is already active');
        }

        $resource->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        // Disparar evento
        event(new ResourceActivated($resource));

        return $resource;
    }

    /**
     * ✅ Métodos privados para lógica interna
     */
    private function processInitialSetup(Example $resource): void
    {
        // Lógica compleja de setup inicial
        // ...
    }

    private function notifyCreation(Example $resource): void
    {
        Mail::to($resource->owner)->send(new ResourceCreated($resource));
    }

    private function validateCategoryChange(Example $resource, int $newCategoryId): void
    {
        // Reglas de negocio para cambio de categoría
        if ($resource->hasActiveOrders()) {
            throw new \DomainException('Cannot change category with active orders');
        }
    }

    private function logAudit(string $event, Example $resource): void
    {
        \App\Services\Core\AuditService::log(
            $event,
            "Resource {$resource->name} created",
            'info',
            ['resource_id' => $resource->id]
        );
    }
}

/**
 * ✅ PATRÓN RECOMENDADO: Un Service por entidad principal
 * 
 * - ExampleService para modelo Example
 * - TenantService para modelo Tenant
 * - AuthService para lógica de autenticación
 * 
 * Evita "God Services" que hacen todo.
 */
