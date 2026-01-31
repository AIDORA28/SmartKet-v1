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
        // 1. Tabla de Roles (Dinámicos)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->string('name'); // Ej: Administrador, Vendedor, Cocinero
            $table->string('description')->nullable();
            $table->string('vertical')->nullable(); // Ej: horeca, retail
            $table->timestamps();
        });

        // 2. Tabla de Permisos (Catálogo de habilidades)
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->string('name')->unique(); // Ej: products.create, sales.manage
            $table->string('display_name');
            $table->string('group_name')->nullable(); // Ej: Inventario, Ventas
            $table->timestamps();
        });

        // 3. Relación Permiso <-> Rol
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });

        // 4. Transformar Staff para multisede
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });

        // 5. Relación Staff <-> Sucursal (Para empleados que trabajan en varias sedes)
        Schema::create('branch_staff', function (Blueprint $table) {
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->primary(['branch_id', 'staff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_staff');
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable(false)->change();
        });
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
