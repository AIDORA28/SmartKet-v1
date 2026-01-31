<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\Table;
use App\Models\Branch;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PolleriaService
{
    public function getMenu(): \Illuminate\Database\Eloquent\Collection
    {
        Log::info('polleria.menu.fetch');
        return Product::where('is_active', true)->get();
    }

    public function createOrder(array $payload): Order
    {
        Log::info('polleria.order.create', ['table' => Arr::get($payload, 'table')]);
        
        // Buscamos o creamos la mesa por nombre simple para el MVP
        $table = Table::firstOrCreate([
            'name' => Arr::get($payload, 'table'),
            'branch_id' => 1 // Asumimos sucursal 1 para el MVP
        ], ['capacity' => 4]);

        return Order::create([
            'branch_id' => 1,
            'table_id' => $table->id,
            'status' => 'pending',
            // En el futuro asociaríamos el staff_id del usuario autenticado
        ]);
    }

    public function getKitchenOrders(): \Illuminate\Database\Eloquent\Collection
    {
        Log::info('polleria.kitchen.orders.fetch');
        return Order::whereIn('status', ['pending', 'preparing'])->with('items.product')->get();
    }

    public function markOrderPrepared(int $orderId): array
    {
        Log::info('polleria.kitchen.order.prepared', ['order_id' => $orderId]);
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'prepared']);
        
        return [
            'order_id' => $orderId,
            'status' => 'prepared',
        ];
    }

    public function registerSale(array $payload): \App\Models\Sale
    {
        Log::info('polleria.sale.register', ['payment_method' => Arr::get($payload, 'payment_method')]);
        
        return \App\Models\Sale::create([
            'branch_id' => 1,
            'payment_method' => Arr::get($payload, 'payment_method'),
            'total' => 0, // En producción calcularíamos el total de los items
        ]);
    }

    public function listPlans(): array
    {
        return [
            [ 'id' => 'starter', 'name' => 'Starter', 'price' => 0 ],
            [ 'id' => 'pro', 'name' => 'Pro', 'price' => 79 ],
        ];
    }

    public function listBranches(): \Illuminate\Database\Eloquent\Collection
    {
        Log::info('polleria.branches.fetch');
        return Branch::all();
    }

    public function activatePlan(string $planId): array
    {
        Log::info('polleria.plan.activate', ['plan_id' => $planId]);
        return [ 'message' => 'Plan activado' ];
    }
}
