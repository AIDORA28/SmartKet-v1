<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FiscalSetting;

class SetupController extends Controller
{
    public function fiscalShow()
    {
        $current = FiscalSetting::query()->latest('id')->first();
        return response()->json([
            'ruc' => $current?->ruc,
            'razon_social' => $current?->razon_social,
            'comprobante_default' => $current?->comprobante_default,
            'boleta_simple_enabled' => (bool)($current?->boleta_simple_enabled ?? true),
        ]);
    }

    public function fiscalSave(Request $request)
    {
        $data = $request->validate([
            'ruc' => ['nullable','regex:/^\d{11}$/'],
            'razon_social' => 'nullable|string|min:2|max:255',
            'comprobante_default' => 'nullable|string|in:boleta,factura',
            'boleta_simple_enabled' => 'nullable|boolean',
        ]);

        $settings = FiscalSetting::query()->latest('id')->first() ?? new FiscalSetting();
        $settings->ruc = $data['ruc'] ?? $settings->ruc;
        $settings->razon_social = $data['razon_social'] ?? $settings->razon_social;
        $settings->comprobante_default = $data['comprobante_default'] ?? $settings->comprobante_default;
        if (array_key_exists('boleta_simple_enabled', $data)) {
            $settings->boleta_simple_enabled = (bool) $data['boleta_simple_enabled'];
        }
        $settings->save();

        return response()->json(['message' => 'Datos fiscales guardados', 'data' => $settings]);
    }
}

