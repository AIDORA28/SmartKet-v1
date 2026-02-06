<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Core\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        Log::info("Intento de login para: " . $credentials['login_id']);

        $result = $this->authService->login(
            $credentials['login_id'],
            $credentials['password']
        );

        Log::info("Login exitoso para: " . $credentials['login_id']);

        $token = $result['token'];

        // Devolvemos el token TANTO en el JSON como en la cookie para máxima compatibilidad
        return response()->json([
            'token' => $token,
            'tenant_id' => $result['tenant']->id,
            'message' => 'Login exitoso',
        ])->cookie(
            'smartket_token', 
            $token, 
            60 * 24 * 30, // 30 días
            '/', 
            null, 
            config('app.env') === 'production', // Secure solo en prod
            true, // HttpOnly
            false, 
            'lax'
        );
    }

    public function me(Request $request)
    {
        Log::info("Ejecutando AuthController@me (versión simplificada vía Service)");
        
        $user = Auth::user();
        $tenant = \App\Models\Core\Tenant::current();
        
        // Resolución resiliente de tenant si no está en el contexto
        if (!$tenant && $request->hasHeader('X-Tenant-ID')) {
            $tenant = \App\Models\Core\Tenant::find($request->header('X-Tenant-ID'));
            if ($tenant) $tenant->makeCurrent();
        }

        try {
            $responseData = $this->authService->getUserProfileData($user, $tenant);
            Log::info("AuthController@me: Respuesta preparada exitosamente.");
            return response()->json($responseData);
        } catch (\Exception $e) {
            Log::error("AuthController@me error: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Sesión cerrada exitosamente'])
            ->withoutCookie('smartket_token');
    }
}
