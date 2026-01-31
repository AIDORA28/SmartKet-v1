<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
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
        Log::info("Ejecutando AuthController@me (versión resiliente con multitenant context)");
        $user = Auth::user();
        
        // Intentamos obtener el tenant actual. Si no está seteado por el middleware,
        // intentamos resolverlo manualmente desde el header X-Tenant-ID o del primer negocio del dueño
        $tenant = \App\Models\Tenant::current();
        
        if (!$tenant && $request->hasHeader('X-Tenant-ID')) {
            $headerTenantId = $request->header('X-Tenant-ID');
            $tenant = \App\Models\Tenant::find($headerTenantId);
            if ($tenant) $tenant->makeCurrent();
        }

        if (!$user) {
            Log::error("AuthController@me: Usuario no autenticado.");
            return response()->json(['message' => 'No autorizado'], 401);
        }

        if (!$tenant && !($user instanceof \App\Models\Staff)) {
            $tenant = $user->tenants()->first();
            if ($tenant) $tenant->makeCurrent();
        }

        if (!$tenant) {
            Log::error("AuthController@me: Tenant no encontrado.");
            return response()->json(['message' => 'Negocio no encontrado o no asociado.'], 404);
        }

        // Cargamos la suscripción y el plan asociado.
        $subscription = $tenant->subscriptions()->with('plan')->first();

        // Si el usuario autenticado es un 'Staff', cargamos sus roles desde la BD del tenant.
        // Si es un 'User' (dueño), simplemente le asignamos el rol de 'admin'.
        if ($user instanceof \App\Models\Staff) {
            $tenant->execute(fn() => $user->load('roles'));
            $userRoles = $user->roles->pluck('name')->toArray();
            $accessibleTenants = [$tenant->only(['id', 'nombre_negocio', 'slug', 'rubro'])];
        } else {
            $userRoles = ['admin'];
            // Para el dueño, obtenemos todos sus negocios asociados
            $accessibleTenants = $user->tenants()->get(['tenants.id', 'nombre_negocio', 'slug', 'rubro']);
        }

        $responseData = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? $user->username,
                'roles' => $userRoles,
            ],
            'tenant' => [
                'id' => $tenant->id,
                'nombre_negocio' => $tenant->nombre_negocio,
                'slug' => $tenant->slug,
                'rubro' => $tenant->rubro,
                'setup_complete' => (bool)$tenant->setup_complete,
                'plan' => $subscription->plan ?? null,
                'subscription_status' => $subscription->status ?? 'inactive',
            ],
            'accessible_tenants' => $accessibleTenants
        ];

        Log::info("AuthController@me: Respuesta JSON preparada exitosamente.");
        return response()->json($responseData);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Sesión cerrada exitosamente'])
            ->withoutCookie('smartket_token');
    }
}
