<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        // Si no está autenticado o no es un Staff con el permiso
        if (!$user || !method_exists($user, 'hasPermission') || !$user->hasPermission($permission)) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción.',
                'required_permission' => $permission
            ], 403);
        }

        return $next($request);
    }
}
