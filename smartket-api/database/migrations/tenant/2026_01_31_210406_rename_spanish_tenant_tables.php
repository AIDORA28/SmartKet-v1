<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('mesas', 'tables');
        Schema::rename('pedidos', 'orders');
        Schema::rename('pedido_items', 'order_items');
        Schema::rename('sale_details', 'sale_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('tables', 'mesas');
        Schema::rename('orders', 'pedidos');
        Schema::rename('order_items', 'pedido_items');
        Schema::rename('sale_items', 'sale_details');
    }
};
