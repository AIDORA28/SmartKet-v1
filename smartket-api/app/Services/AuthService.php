<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffIndex;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Autentica a un usuario, ya sea un Dueño (User) o un Empleado (Staff).
     *
     * @param string $loginId Puede ser un email (dueño) o un username (empleado).
     * @param string $password
     * @return array Devuelve un array con el token y los datos del usuario/tenant.
     */
    public function login(string $loginId, string $password): array
    {
        // Intento 1: Autenticar como Dueño (User)
        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginId)->first();

            if ($user && Hash::check($password, $user->password)) {
                $result = $this->generateTokenForUser($user);
                AuditService::log('auth:login', "Login exitoso como dueño: {$user->email}", 'info', ['user_id' => $user->id], $result['tenant']?->id);
                return $result;
            }
        }

        // Intento 2: Autenticar como Empleado (Staff)
        $staffIndex = StaffIndex::where('username', $loginId)->first();

        if ($staffIndex) {
            $tenant = $staffIndex->tenant;
            
            $staffMember = $tenant->execute(function () use ($loginId) {
                return Staff::where('username', $loginId)->first();
            });

            if ($staffMember && Hash::check($password, $staffMember->password)) {
                $result = $this->generateTokenForStaff($staffMember, $tenant);
                AuditService::log('auth:login', "Login exitoso como staff: {$staffMember->username}", 'info', ['staff_id' => $staffMember->id], $tenant->id);
                return $result;
            }
        }

        // Si ambos intentos fallan, registramos el fallo de seguridad
        AuditService::log('auth:failure', "Intento de login fallido para ID: {$loginId}", 'warning', ['login_id' => $loginId, 'status' => 'failure']);

        throw ValidationException::withMessages([
            'login_id' => ['Las credenciales proporcionadas son incorrectas.'],
        ]);
    }

    private function generateTokenForUser(User $user): array
    {
        // Rotación de tokens: Revocamos tokens anteriores para implementar sesión única activa
        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;
        $tenant = $user->tenants()->first(); // Asumimos que un dueño solo pertenece a un tenant por ahora

        return [
            'token' => $token,
            'user_type' => 'owner',
            'user' => $user,
            'tenant' => $tenant,
        ];
    }

    private function generateTokenForStaff(Staff $staff, $tenant): array
    {
        // Creamos el token en el contexto del tenant para que esté asociado a él.
        $tokenResult = $tenant->execute(function () use ($staff) {
            // Rotación de tokens para staff
            $staff->tokens()->delete();
            return $staff->createToken('api-token');
        });
        
        $staff->load('roles'); // Cargar los roles del empleado

        return [
            'token' => $tokenResult->plainTextToken,
            'user_type' => 'staff',
            'user' => $staff,
            'tenant' => $tenant,
        ];
    }
    public function getUserProfileData($user, $tenant = null): array
    {
        if (!$user) {
            throw new \Exception('Usuario no proporcionado.');
        }

        // Si no hay tenant activo, intentamos resolver el primero disponible para el dueño
        if (!$tenant && !($user instanceof \App\Models\Staff)) {
            $tenant = $user->tenants()->first();
            if ($tenant) $tenant->makeCurrent();
        }

        if (!$tenant) {
            throw new \Exception('Negocio no activo o no accesible.');
        }

        // Cargamos la suscripción y el plan asociado desde la BD Landlord (donde vive Tenant)
        $subscription = $tenant->subscriptions()->with('plan')->first();

        // Diferenciamos lógica según el tipo de usuario (Dueño vs Staff)
        $userBranches = [];
        
        if ($user instanceof \App\Models\Staff) {
            // El staff siempre actúa dentro de UN solo tenant (contexto DB tenant)
            $tenant->execute(function() use ($user) {
                $user->load('roles', 'branches');
            });
            
            $userRoles = $user->roles->pluck('name')->toArray();
            
            // Si tiene branches asignadas, las usamos. Si es admin y no tiene, quizás ver todas?
            // Por seguridad explícita, solo devolvemos las asignadas en branch_staff.
            // Si la tabla branch_staff está vacía para él, no ve nada.
            $userBranches = $user->branches->map(fn($b) => $b->only(['id', 'name', 'is_active']))->toArray();
            
            $accessibleTenants = [$tenant->only(['id', 'business_name', 'slug', 'business_type'])];
        } else {
            // El dueño puede tener múltiples negocios y ve TODAS las sucursales del tenant activo
            $userRoles = ['admin'];
            $accessibleTenants = $user->tenants()->get(['tenants.id', 'business_name', 'slug', 'business_type']);
            
            // Cargar todas las sucursales del tenant activo
            $userBranches = $tenant->execute(fn() => \App\Models\Branch::where('is_active', true)->get(['id', 'name', 'is_active'])->toArray());
        }

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? $user->username,
                'roles' => $userRoles,
                'branches' => $userBranches, // <--- NUEVO CAMPO
            ],
            'tenant' => [
                'id' => $tenant->id,
                'business_name' => $tenant->business_name,
                'slug' => $tenant->slug,
                'business_type' => $tenant->business_type,
                'setup_complete' => (bool)$tenant->setup_complete,
                'plan' => $subscription->plan ?? null,
                'subscription_status' => $subscription->status ?? 'inactive',
            ],
            'accessible_tenants' => $accessibleTenants
        ];
    }
}
