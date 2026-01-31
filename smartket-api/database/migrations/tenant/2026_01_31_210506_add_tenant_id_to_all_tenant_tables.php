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
        $tables = [
            'categories', 'branches', 'cash_registers', 'staff', 
            'products', 'tables', 'orders', 'order_items', 
            'sales', 'sale_items', 'fiscal_settings'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'tenant_id')) {
                    $table->unsignedBigInteger('tenant_id')->nullable()->index();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'categories', 'branches', 'cash_registers', 'staff', 
            'products', 'tables', 'orders', 'order_items', 
            'sales', 'sale_items', 'fiscal_settings'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'tenant_id')) {
                    $table->dropColumn('tenant_id');
                }
            });
        }
    }
};
