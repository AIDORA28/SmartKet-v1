<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Servicio del Módulo Pollería.
 * Contiene la lógica de negocio mínima (MVP) para menú, pedidos, cocina y ventas.
 * Mantiene separación de responsabilidades: los controladores sólo validan y delegan aquí.
 */
class PolleriaService
{
    /**
     * Devuelve el menú base.
     * @return array<int,array<string,mixed>>
     */
    public function getMenu(): array
    {
        Log::info('polleria.menu.fetch');
        return [
            [ 'id' => 1, 'name' => '1/4 de Pollo', 'price' => 18.00 ],
            [ 'id' => 2, 'name' => '1/2 Pollo', 'price' => 32.00 ],
            [ 'id' => 3, 'name' => 'Pollo Entero', 'price' => 58.00 ],
            [ 'id' => 4, 'name' => 'Papas Fritas', 'price' => 6.00 ],
            [ 'id' => 5, 'name' => 'Inca Kola 1L', 'price' => 9.00 ],
        ];
    }

    /**
     * Registra un pedido (stub) y devuelve la representación.
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function createOrder(array $payload): array
    {
        Log::info('polleria.order.create', ['table' => Arr::get($payload, 'table')]);
        return [
            'id' => (int) floor(microtime(true) * 1000) % 1000000,
            'table' => Arr::get($payload, 'table'),
            'items' => Arr::get($payload, 'items', []),
            'status' => 'pending',
        ];
    }

    /**
     * Pedidos pendientes para cocina (stub).
     * @return array<int,array<string,mixed>>
     */
    public function getKitchenOrders(): array
    {
        Log::info('polleria.kitchen.orders.fetch');
        return [
            [
                'id' => 101,
                'table' => 'A1',
                'items' => [
                    [ 'product_id' => 1, 'name' => '1/4 de Pollo', 'qty' => 2 ],
                    [ 'product_id' => 4, 'name' => 'Papas Fritas', 'qty' => 2 ],
                ],
            ],
            [
                'id' => 102,
                'table' => 'B3',
                'items' => [
                    [ 'product_id' => 2, 'name' => '1/2 Pollo', 'qty' => 1 ],
                    [ 'product_id' => 5, 'name' => 'Inca Kola 1L', 'qty' => 1 ],
                ],
            ],
        ];
    }

    /**
     * Marca un pedido como preparado (stub).
     * @param int $orderId
     * @return array<string,mixed>
     */
    public function markOrderPrepared(int $orderId): array
    {
        Log::info('polleria.kitchen.order.prepared', ['order_id' => $orderId]);
        return [
            'order_id' => $orderId,
            'status' => 'prepared',
        ];
    }

    /**
     * Registra una venta (stub) y devuelve la representación.
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function registerSale(array $payload): array
    {
        Log::info('polleria.sale.register', ['payment_method' => Arr::get($payload, 'payment_method')]);
        return [
            'id' => (int) floor(microtime(true) * 1000) % 1000000,
            'payment_method' => Arr::get($payload, 'payment_method'),
            'items' => Arr::get($payload, 'items', []),
            'total' => null,
        ];
    }

    /**
     * Lista de planes (stub).
     * @return array<int,array<string,mixed>>
     */
    public function listPlans(): array
    {
        return Cache::remember('polleria_plans', 3600, function () {
            Log::info('polleria.plans.fetch.cache_miss');
            return [
                [ 'id' => 'starter', 'name' => 'Starter', 'price' => 0 ],
                [ 'id' => 'pro', 'name' => 'Pro', 'price' => 79 ],
            ];
        });
    }

    /**
     * Lista de sucursales (stub).
     * @return array<int,array<string,mixed>>
     */
    public function listBranches(): array
    {
        Log::info('polleria.branches.fetch');
        return [
            [ 'id' => 1, 'name' => 'Principal', 'address' => 'Av. Siempre Viva 123' ],
        ];
    }

    /**
     * Activa plan (stub).
     * @param string $planId
     * @return array<string,string>
     */
    public function activatePlan(string $planId): array
    {
        Log::info('polleria.plan.activate', ['plan_id' => $planId]);
        return [ 'message' => 'Plan activado' ];
    }
}
