<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Legacy stub: superseded by 2025_11_08_000001_create_core_tenant_tables
        // Avoid duplicate table creation in test/dev by making this a no-op.
        if (Schema::hasTable('tenants')) {
            return;
        }
        // Intentionally left blank
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
