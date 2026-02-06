<?php

namespace App\Http\Controllers\Api\Core;

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
            'legal_name' => $current?->legal_name,
            'default_receipt_type' => $current?->default_receipt_type,
            'boleta_simple_enabled' => (bool)($current?->boleta_simple_enabled ?? true),
        ]);
    }

    public function fiscalSave(Request $request)
    {
        $data = $request->validate([
            'ruc' => ['nullable','regex:/^\d{11}$/'],
            'legal_name' => 'nullable|string|min:2|max:255',
            'default_receipt_type' => 'nullable|string|in:boleta,factura',
            'boleta_simple_enabled' => 'nullable|boolean',
        ]);

        $settings = FiscalSetting::query()->latest('id')->first() ?? new FiscalSetting();
        $settings->ruc = $data['ruc'] ?? $settings->ruc;
        $settings->legal_name = $data['legal_name'] ?? $settings->legal_name;
        $settings->default_receipt_type = $data['default_receipt_type'] ?? $settings->default_receipt_type;
        if (array_key_exists('boleta_simple_enabled', $data)) {
            $settings->boleta_simple_enabled = (bool) $data['boleta_simple_enabled'];
        }
        $settings->save();

        return response()->json(['message' => 'Datos fiscales guardados', 'data' => $settings]);
    }
}

