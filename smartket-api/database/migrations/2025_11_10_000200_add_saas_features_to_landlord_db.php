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
        // 1. Mejoras a la tabla `tenants` (de forma segura)
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->unique()->after('nombre_negocio')->comment('Identificador único para URL y login de staff.');
            }
            if (!Schema::hasColumn('tenants', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('rubro')->comment('Ruta al logo personalizado del negocio.');
            }
        });

        // 2. Nueva tabla `staff_index` para el "Login Mágico"
        if (!Schema::hasTable('staff_index')) {
            Schema::create('staff_index', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 3. Nueva tabla `subscriptions` para gestionar la facturación
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
                $table->foreignId('plan_id')->constrained('plans');
                $table->string('status')->default('trialing')->comment('ej: trialing, active, past_due, canceled');
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('current_period_ends_at')->nullable();
                $table->string('provider')->nullable()->comment('Ej: Stripe, MercadoPago');
                $table->string('provider_subscription_id')->nullable()->comment('ID de la suscripción en la plataforma de pago');
                $table->timestamps();
            });
        }

        // 4. Nueva tabla `system_events` para auditoría a nivel de sistema
        if (!Schema::hasTable('system_events')) {
            Schema::create('system_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_type')->index()->comment('ej: tenant.created, subscription.started');
                $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('set null');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->json('metadata')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 5. Nueva tabla `permissions` para control de acceso granular (RBAC)
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('Nombre legible para la UI, ej: "Anular Venta"');
                $table->string('key')->unique()->comment('Clave para usar en el código, ej: sales.void');
                $table->string('module')->default('General')->comment('Para agrupar permisos en la UI');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('system_events');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('staff_index');

        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('tenants', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};
