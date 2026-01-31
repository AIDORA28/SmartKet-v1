<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffIndex;
use App\Models\User;
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
                return $this->generateTokenForUser($user);
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
                return $this->generateTokenForStaff($staffMember, $tenant);
            }
        }

        // Si ambos intentos fallan
        throw ValidationException::withMessages([
            'login_id' => ['Las credenciales proporcionadas son incorrectas.'],
        ]);
    }

    private function generateTokenForUser(User $user): array
    {
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
}
