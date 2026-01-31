<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of all available modules.
     */
    public function index()
    {
        return Module::all();
    }

    /**
     * Sync the modules for the current tenant.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.quantity' => 'required|integer|min:0',
        ]);

        $tenant = tenant();
        $syncData = collect($validated['modules'])->mapWithKeys(function ($module) {
            return [$module['id'] => ['quantity' => $module['quantity']]];
        });

        $tenant->modules()->sync($syncData);

        return response()->json(['message' => 'Módulos del tenant actualizados.']);
    }
}
