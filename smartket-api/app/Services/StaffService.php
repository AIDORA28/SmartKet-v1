<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffIndex;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StaffService
{
    public function createStaff(Tenant $tenant, array $data): Staff
    {
        $this->validatePlanLimits($tenant);

        $username = $this->generateUniqueUsername($data['name'], $tenant);

        // La creación del Staff y su entrada en el índice deben ser atómicas.
        // Usamos una transacción en la BD principal.
        return DB::transaction(function () use ($tenant, $data, $username) {
            $staffMember = $tenant->execute(function () use ($data, $username) {
                $staff = Staff::create([
                    'name' => $data['name'],
                    'username' => $username,
                    'password' => Hash::make($data['password']),
                    'branch_id' => null, // Ya no usamos branch_id principal obligatorio, todo va por tabla pivote
                ]);
                
                if (isset($data['roles'])) {
                    $staff->roles()->sync($data['roles']);
                }
                
                if (isset($data['branches'])) {
                    $staff->branches()->sync($data['branches']);
                }
                
                return $staff;
            });

            // Crear la entrada en el "puente" (base de datos principal)
            StaffIndex::create([
                'username' => $username,
                'tenant_id' => $tenant->id,
            ]);

            return $staffMember;
        });
    }

    public function updateStaff(Tenant $tenant, int $staffId, array $data): Staff
    {
        return $tenant->execute(function () use ($staffId, $data) {
            $staffMember = Staff::findOrFail($staffId);

            // Nota: Por ahora, no permitimos cambiar el username para mantener la simplicidad.
            // Si se permitiera, aquí necesitaríamos lógica para actualizar también el `staff_index`.
            
            if (isset($data['name'])) {
                $staffMember->name = $data['name'];
            }
            if (!empty($data['password'])) {
                $staffMember->password = Hash::make($data['password']);
            }
            $staffMember->save();

            if (isset($data['roles'])) {
                $staffMember->roles()->sync($data['roles']);
            }

            if (isset($data['branches'])) {
                $staffMember->branches()->sync($data['branches']);
            }
            
            return $staffMember;
        });
    }

    public function deleteStaff(Tenant $tenant, int $staffId): void
    {
        DB::transaction(function () use ($tenant, $staffId) {
            $staffMember = $tenant->execute(function () use ($staffId) {
                $staff = Staff::findOrFail($staffId);
                $username = $staff->username;
                $staff->delete();
                return $username;
            });

            // Eliminar la entrada del "puente"
            if ($staffMember) {
                StaffIndex::where('username', $staffMember)->delete();
            }
        });
    }

    private function validatePlanLimits(Tenant $tenant): void
    {
        // Cargar la suscripción y el plan asociado
        $subscription = $tenant->subscriptions()->first(); // Asumimos una suscripción por tenant
        if (!$subscription || !$subscription->plan) {
            throw new \Exception("El tenant no tiene una suscripción o plan válido.");
        }

        $plan = $subscription->plan;
        $limits = $plan->features ?? [];
        $maxUsers = $limits['max_total_users'] ?? 1;
        
        // El conteo de usuarios es el dueño (1) + el número de staff
        $currentUsers = $tenant->execute(fn() => Staff::count()) + 1;

        if ($currentUsers >= $maxUsers) {
            throw ValidationException::withMessages([
                'limit' => 'Has alcanzado el límite de usuarios de tu plan. Por favor, actualiza tu suscripción.',
            ]);
        }
    }

    private function generateUniqueUsername(string $name, Tenant $tenant): string
    {
        $baseUsername = Str::slug($name) . '-' . $tenant->slug;
        $username = $baseUsername;
        $counter = 1;

        // Verificamos contra el índice global para garantizar unicidad en toda la plataforma.
        while (StaffIndex::where('username', $username)->exists()) {
            $username = $baseUsername . '-' . $counter++;
        }

        return $username;
    }
}
