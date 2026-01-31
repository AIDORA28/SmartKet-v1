<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Legacy stub: pivot table now defined in core tenant tables migration
        if (Schema::hasTable('tenant_user')) {
            return;
        }
        // Intentionally left blank
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
    }
};
