<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Legacy stub: table now defined in core tenant tables migration
        if (Schema::hasTable('audit_events')) {
            return;
        }
        // Intentionally left blank
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
    }
};

