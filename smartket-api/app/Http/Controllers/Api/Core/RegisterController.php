<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Core\AuthService;
use App\Services\Core\TenantService;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    protected TenantService $tenantService;
    protected AuthService $authService;

    public function __construct(TenantService $tenantService, AuthService $authService)
    {
        $this->tenantService = $tenantService;
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        Log::info('Iniciando proceso de registro para: ' . $data['email']);

        try {
            // 1. Usar el TenantService para crear el negocio (esto ya no es un comando)
            $this->tenantService->createTenant(
                $data['business_name'],
                $data['email'],
                $data['password'],
                $data['business_type']
            );

            // 2. Si la creación es exitosa, usar el AuthService para iniciar sesión
            $result = $this->authService->login($data['email'], $data['password']);

            Log::info('Registro y login automático exitoso para: ' . $data['email']);

            // 3. Devolver la misma respuesta que el endpoint de login
            return response()->json([
                'token' => $result['token'],
                'tenant_id' => $result['tenant']->id,
            ], 201);

        } catch (\Exception $e) {
            Log::error('El proceso de registro falló para: ' . $data['email'] . '. Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor al crear el negocio.'], 500);
        }
    }
}
