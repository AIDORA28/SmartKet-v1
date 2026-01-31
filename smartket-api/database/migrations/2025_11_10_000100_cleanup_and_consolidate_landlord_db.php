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
        // 1. Eliminar la tabla `audit_events` ya que será reemplazada por `system_events`.
        Schema::dropIfExists('audit_events');

        // 2. Eliminar las columnas de suscripción de la tabla `tenants`.
        // Su nuevo hogar es la tabla `subscriptions`.
        if (Schema::hasTable('tenants')) {
            if (Schema::hasColumn('tenants', 'plan_id')) {
                Schema::table('tenants', function (Blueprint $table) {
                    // Es importante eliminar primero la clave foránea antes de la columna.
                    $table->dropForeign(['plan_id']);
                    $table->dropColumn('plan_id');
                });
            }
            if (Schema::hasColumn('tenants', 'trial_ends_at')) {
                Schema::table('tenants', function (Blueprint $table) {
                    $table->dropColumn('trial_ends_at');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar la tabla `audit_events` (si es necesario para revertir)
        if (!Schema::hasTable('audit_events')) {
            Schema::create('audit_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('set null');
                $table->string('event_type');
                $table->string('route')->nullable();
                $table->text('message')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        // Restaurar las columnas en la tabla `tenants`
        if (!Schema::hasColumn('tenants', 'plan_id')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null');
            });
        }
        if (!Schema::hasColumn('tenants', 'trial_ends_at')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->timestamp('trial_ends_at')->nullable();
            });
        }
    }
};
