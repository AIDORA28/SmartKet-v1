<?php

namespace App\Http\Controllers\Api;

use App\Services\PolleriaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controlador Pollería (MVP): valida requests y delega en PolleriaService.
 */
class PolleriaController extends BaseController
{
    public function __construct(private readonly PolleriaService $service)
    {
    }

    // Menú de productos visibles para Mesero y Caja
    public function menu()
    {
        return response()->json($this->service->getMenu());
    }

    // Registrar un pedido (Mesero)
    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|string|max:30',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $order = $this->service->createOrder($validated);

        return response()->json([
            'message' => 'Pedido registrado',
            'order' => $order,
        ], 201);
    }

    // Pedidos pendientes para Cocina
    public function kitchenOrders()
    {
        return response()->json($this->service->getKitchenOrders());
    }

    // Marcar pedido como preparado
    public function markOrderPrepared(int $orderId)
    {
        $result = $this->service->markOrderPrepared($orderId);
        return response()->json([
            'message' => 'Pedido marcado como preparado',
            'order_id' => $result['order_id'],
            'status' => $result['status'],
        ]);
    }

    // Registrar una venta en Caja
    public function sales(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        $sale = $this->service->registerSale($validated);

        return response()->json([
            'message' => 'Venta registrada',
            'sale' => $sale,
        ], 201);
    }

    // Planes disponibles (stub)
    public function plans()
    {
        return response()->json($this->service->listPlans());
    }

    // Sucursales del negocio (stub)
    public function branches()
    {
        return response()->json($this->service->listBranches());
    }

    // Activar un plan (stub)
    public function activatePlan(Request $request)
    {
        $data = $request->validate([
            'plan_id' => 'required|string',
        ]);

        $result = $this->service->activatePlan($data['plan_id']);
        return response()->json($result);
    }
}
