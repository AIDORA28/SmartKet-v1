<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('tenants') || ! Schema::hasColumn('tenants', 'db_password')) {
            return;
        }

        $driver = DB::getDriverName();
        try {
            if ($driver === 'pgsql') {
                DB::statement('ALTER TABLE tenants ALTER COLUMN db_password TYPE TEXT');
            } elseif ($driver === 'mysql') {
                DB::statement('ALTER TABLE tenants MODIFY db_password TEXT NULL');
            } elseif ($driver === 'sqlite') {
                // SQLite cannot alter column type easily; skip in tests
            }
        } catch (\Throwable $e) {
            // swallow to avoid breaking migration pipelines
        }
    }

    public function down(): void
    {
        // No-op: do not revert column type
    }
};

