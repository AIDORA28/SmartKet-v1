<?php

namespace App\Services;

use App\Models\AuditEvent;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Registra un evento de auditoría en el sistema maestro.
     * 
     * @param string $eventType Categoría del evento (auth:login, resource:update, etc)
     * @param string|null $message Descripción legible del evento
     * @param string $severity Nivel de severidad (info, warning, critical)
     * @param array $meta Información técnica adicional
     * @param int|null $tenantId ID del inquilino (opcional)
     * @return void
     */
    public static function log(
        string $eventType, 
        ?string $message = null, 
        string $severity = 'info', 
        array $meta = [], 
        ?int $tenantId = null
    ): void {
        try {
            AuditEvent::create([
                'user_id' => auth()->id(),
                'tenant_id' => $tenantId ?? session('tenant_id'),
                'event_type' => $eventType,
                'severity' => $severity,
                'status' => $meta['status'] ?? 'success',
                'route' => Request::path(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'message' => $message,
                'meta' => !empty($meta) ? $meta : null,
            ]);
        } catch (\Exception $e) {
            // No bloqueamos la ejecución si falla el log, pero lo reportamos al log interno
            Log::error("Fallo al registrar evento de auditoría: " . $e->getMessage(), [
                'event_type' => $eventType,
                'message' => $message
            ]);
        }
    }
}
