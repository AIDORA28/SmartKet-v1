<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Skip if base table does not exist (superseded by core tenant tables migration)
        if (!Schema::hasTable('tenants')) {
            return;
        }
        // Agregar columnas nuevas solo si no existen
        if (!Schema::hasColumn('tenants', 'nombre_negocio')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('nombre_negocio')->nullable();
            });
        }

        if (!Schema::hasColumn('tenants', 'plan')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('plan')->nullable();
            });
        }

        if (!Schema::hasColumn('tenants', 'rubro')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('rubro')->nullable();
            });
        }

        if (!Schema::hasColumn('tenants', 'db_name')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('db_name')->nullable()->unique();
            });
        }

        if (!Schema::hasColumn('tenants', 'db_user')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('db_user')->nullable();
            });
        }

        if (!Schema::hasColumn('tenants', 'db_password')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->text('db_password')->nullable();
            });
        }

        if (!Schema::hasColumn('tenants', 'setup_complete')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->boolean('setup_complete')->default(false);
            });
        }

        // Eliminar campos antiguos si existen
        $toDrop = [];
        foreach (['name', 'slug', 'database', 'is_active'] as $column) {
            if (Schema::hasColumn('tenants', $column)) {
                $toDrop[] = $column;
            }
        }
        if (!empty($toDrop)) {
            Schema::table('tenants', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }

    public function down(): void
    {
        // Revertir columnas nuevas si existen
        $toDropNew = [];
        foreach (['nombre_negocio','plan','rubro','db_name','db_user','db_password','setup_complete'] as $column) {
            if (Schema::hasColumn('tenants', $column)) {
                $toDropNew[] = $column;
            }
        }
        if (!empty($toDropNew)) {
            Schema::table('tenants', function (Blueprint $table) use ($toDropNew) {
                $table->dropColumn($toDropNew);
            });
        }

        // Restaurar columnas antiguas solo si no existen
        if (!Schema::hasColumn('tenants', 'name')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('name');
            });
        }
        if (!Schema::hasColumn('tenants', 'slug')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('slug')->unique();
            });
        }
        if (!Schema::hasColumn('tenants', 'database')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('database')->unique();
            });
        }
        if (!Schema::hasColumn('tenants', 'is_active')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }
    }
};
