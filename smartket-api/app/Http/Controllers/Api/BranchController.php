<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class BranchController extends Controller
{
    public function index()
    {
        return Branch::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $branch = Branch::create($validated);

        return response()->json($branch, 201);
    }

    public function show($id)
    {
        return Branch::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'is_active' => 'boolean',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $branch->update($validated);

        return response()->json($branch);
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->noContent();
    }
}
