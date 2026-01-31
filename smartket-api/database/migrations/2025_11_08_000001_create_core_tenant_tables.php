<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tenants (negocios)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_negocio');
            $table->string('plan')->default('basic');
            $table->string('rubro');
            $table->boolean('setup_complete')->default(false)->index();
            $table->unsignedBigInteger('owner_user_id')->nullable()->index();
            // Credenciales de base de datos del tenant (cifrar a nivel de modelo)
            $table->string('db_name')->nullable();
            $table->string('db_user')->nullable();
            $table->string('db_password')->nullable();
            $table->timestamps();
            $table->index(['rubro']);
        });

        // Sucursales
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->timestamps();
            $table->index(['tenant_id']);
        });

        // Relación usuario-tenant y rol
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role')->default('admin');
            $table->timestamps();
            $table->primary(['tenant_id','user_id']);
            $table->index(['role']);
        });

        // Productos (tabla alineada con App\Models\Producto)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('sku')->nullable()->index();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->index(['tenant_id']);
            $table->index(['tenant_id','active']);
        });

        // Pedidos
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('table_code')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->timestamps();
            $table->index(['tenant_id']);
            $table->index(['tenant_id','status']);
        });

        // Items de pedido
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            $table->index(['order_id']);
        });

        // Ventas
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->string('payment_method')->index();
            $table->timestamps();
            $table->index(['tenant_id']);
            $table->index(['tenant_id','payment_method']);
            $table->index(['created_at']);
        });

        // Items de venta
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            $table->index(['sale_id']);
        });

        // Configuración fiscal por tenant
        Schema::create('fiscal_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('ruc', 11)->nullable()->index();
            $table->string('razon_social')->nullable();
            $table->string('comprobante_default')->nullable();
            $table->boolean('boleta_simple_enabled')->default(true);
            $table->timestamps();
            $table->index(['tenant_id']);
        });

        // Auditoría
        Schema::create('audit_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('event_type');
            $table->string('route')->nullable();
            $table->text('message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['tenant_id']);
            $table->index(['event_type']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
        Schema::dropIfExists('fiscal_settings');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('tenants');
    }
};

