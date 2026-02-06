<?php

namespace App\Http\Controllers\Api\Core;

use App\Services\Core\ExampleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * ✅ EJEMPLO DE CONTROLLER CORRECTO (Patrón Delgado)
 * 
 * Responsabilidades únicas:
 * 1. Validar la petición entrante
 * 2. Delegar lógica al Service
 * 3. Devolver respuesta JSON
 */
class ExampleController extends Controller
{
    public function __construct(
        private ExampleService $service
    ) {}

    /**
     * GET /api/examples
     * Listar recursos con paginación
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $results = $this->service->list($perPage);
        
        return response()->json($results);
    }

    /**
     * POST /api/examples
     * Crear nuevo recurso
     */
    public function store(Request $request): JsonResponse
    {
        // ✅ Validación en Controller (correcto)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // ✅ Lógica delegada a Service (correcto)
        $resource = $this->service->create($validated);

        return response()->json($resource, 201);
    }

    /**
     * GET /api/examples/{id}
     * Mostrar recurso específico
     */
    public function show(int $id): JsonResponse
    {
        $resource = $this->service->find($id);
        
        if (!$resource) {
            return response()->json([
                'message' => 'Resource not found'
            ], 404);
        }

        return response()->json($resource);
    }

    /**
     * PUT /api/examples/{id}
     * Actualizar recurso
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $resource = $this->service->update($id, $validated);

        return response()->json($resource);
    }

    /**
     * DELETE /api/examples/{id}
     * Eliminar recurso
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        
        return response()->json([
            'message' => 'Resource deleted successfully'
        ], 200);
    }

    /**
     * ✅ Acción personalizada - todavía delgada
     * POST /api/examples/{id}/activate
     */
    public function activate(int $id): JsonResponse
    {
        $resource = $this->service->activate($id);
        
        return response()->json([
            'message' => 'Resource activated',
            'resource' => $resource
        ]);
    }
}

/**
 * ❌ ANTI-PATRÓN: FAT CONTROLLER
 * 
 * Esto NO debes hacerlo:
 */
/*
class BadExampleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // ❌ Lógica de negocio en controller
        $validated = $request->validate([...]);
        
        DB::transaction(function() use ($validated) {
            // ❌ Acceso directo a DB
            $resource = Resource::create($validated);
            
            // ❌ Loops con lógica compleja
            foreach ($resource->dependencies as $dep) {
                if ($dep->needsUpdate()) {
                    $dep->update(['status' => 'processed']);
                }
            }
            
            // ❌ Envío de notificaciones
            Mail::to($resource->owner)->send(new ResourceCreated($resource));
            
            // ❌ Cálculos complejos
            $total = 0;
            foreach ($resource->items as $item) {
                $total += $item->price * $item->quantity;
            }
            $resource->total = $total;
            $resource->save();
        });
        
        return response()->json($resource, 201);
    }
}
*/
