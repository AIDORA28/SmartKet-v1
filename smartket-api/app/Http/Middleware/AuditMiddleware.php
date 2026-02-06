<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Core\AuditService;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo auditamos métodos que cambian estado (POST, PUT, PATCH, DELETE)
        // y que han sido procesados por un usuario autenticado o son intentos de login
        $methodsToAudit = ['POST', 'PUT', 'PATCH', 'DELETE'];
        
        if (in_array($request->method(), $methodsToAudit)) {
            $this->logEvent($request, $response);
        }

        return $response;
    }

    protected function logEvent(Request $request, Response $response): void
    {
        $status = $response->isSuccessful() ? 'success' : 'failure';
        $severity = $response->isSuccessful() ? 'info' : 'warning';
        
        // Evitamos loguear passwords si están en el request
        $payload = $request->except(['password', 'password_confirmation', 'token']);

        $eventType = "api:" . strtolower($request->method()) . ":" . str_replace('/', '.', trim($request->path(), '/'));

        AuditService::log(
            $eventType,
            "Petición {$request->method()} a {$request->path()} completada con estado {$response->getStatusCode()}",
            $severity,
            [
                'status' => $status,
                'status_code' => $response->getStatusCode(),
                'payload_keys' => array_keys($payload),
                // Podríamos añadir más según necesidad, pero cuidando no saturar la BD
            ],
            $request->header('X-Tenant-ID') ?: null
        );
    }
}
