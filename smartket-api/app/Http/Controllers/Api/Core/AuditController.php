<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditEvent;

class AuditController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_type' => 'required|string|max:100',
            'message' => 'nullable|string',
            'route' => 'nullable|string',
            'meta' => 'nullable|array',
        ]);

        $user = $request->user();
        $tenantId = $request->header('X-Tenant-ID');

        $event = AuditEvent::create([
            'user_id' => $user?->id,
            'tenant_id' => $tenantId ? (int) $tenantId : null,
            'event_type' => $data['event_type'],
            'route' => $data['route'] ?? null,
            'message' => $data['message'] ?? null,
            'meta' => isset($data['meta']) ? json_encode($data['meta']) : null,
        ]);

        return response()->json(['id' => $event->id, 'message' => 'Evento registrado']);
    }
}

