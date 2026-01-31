<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tenant_user', function (Blueprint $table) {
            // Eliminar columnas de timestamps no utilizadas en la tabla pivot
            if (Schema::hasColumn('tenant_user', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('tenant_user', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenant_user', function (Blueprint $table) {
            // Restaurar timestamps como columnas nullable
            if (! Schema::hasColumn('tenant_user', 'created_at') && ! Schema::hasColumn('tenant_user', 'updated_at')) {
                $table->timestamps();
            }
        });
    }
};

