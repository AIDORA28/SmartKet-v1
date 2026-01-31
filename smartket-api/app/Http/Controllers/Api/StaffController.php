<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class StaffController extends Controller
{
    protected StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    public function index()
    {
        // El middleware `NeedsTenant` ya ha hecho el cambio de conexión.
        return Staff::with(['roles', 'branches'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'branches' => 'nullable|array',
            'branches.*' => 'exists:branches,id',
        ]);

        $tenant = Tenant::current();
        $staff = $this->staffService->createStaff($tenant, $validated);

        return response()->json($staff->load(['roles', 'branches']), 201);
    }

    public function show(string $id)
    {
        return Staff::with(['roles', 'branches'])->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|nullable|string|min:8',
            'roles' => 'sometimes|required|array',
            'roles.*' => 'exists:roles,id',
            'branches' => 'nullable|array',
            'branches.*' => 'exists:branches,id',
        ]);

        $tenant = Tenant::current();
        $staff = $this->staffService->updateStaff($tenant, $id, $validated);

        return response()->json($staff->load(['roles', 'branches']));
    }

    public function destroy(string $id)
    {
        $tenant = Tenant::current();
        $this->staffService->deleteStaff($tenant, $id);
        
        return response()->noContent();
    }
}
