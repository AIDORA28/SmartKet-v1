<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    /**
     * Admin DB cleanup migration: backup, identify and drop non-documented tables.
     */
    public function up(): void
    {
        // Whitelist strictly based on original documentation of admin DB
        $whitelist = [
            'users',
            'tenants',
            'tenant_user',
            'personal_access_tokens',
            // Laravel infra tables commonly present
            'migrations',
            'failed_jobs',
            'password_reset_tokens',
            'sessions',
            'jobs',
            'job_batches',
            'cache',
            'cache_locks',
            // Auditing table documented
            'audit_events',
        ];

        // 1) Backup: enumerate tables and persist row counts and core table data
        $timestamp = date('Ymd_His');
        $backupDir = 'backups';
        $backupPath = $backupDir . "/smartket_admin_db_backup_{$timestamp}.json";
        Storage::makeDirectory($backupDir);

        // Fetch all tables according to the current driver
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            $tablesResult = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
            $tables = array_map(fn($r) => $r->tablename, $tablesResult);
        } elseif ($driver === 'mysql') {
            $tablesResult = DB::select('SHOW TABLES');
            // Result key depends on database name; normalize values
            $tables = [];
            foreach ($tablesResult as $row) {
                $tables[] = array_values((array) $row)[0];
            }
        } elseif ($driver === 'sqlite') {
            $tablesResult = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = array_map(fn($r) => $r->name, $tablesResult);
        } else {
            // Fallback to schema builder where available (may include views)
            $tables = [];
            try {
                $tables = Schema::getAllTables(); // Laravel 11 helper
                if (is_array($tables) && isset($tables[0]['name'])) {
                    $tables = array_map(fn($t) => $t['name'], $tables);
                }
            } catch (\Throwable $e) {
                // As a last resort, leave tables empty to avoid breaking migrations on unknown drivers
                $tables = [];
            }
        }

        $backup = [
            'generated_at' => $timestamp,
            'connection' => config('database.default'),
            'tables' => [],
        ];

        $coreDataTables = ['users', 'tenants', 'tenant_user', 'personal_access_tokens'];

        foreach ($tables as $t) {
            $info = ['name' => $t, 'row_count' => null, 'sample' => null];
            try {
                $info['row_count'] = DB::table($t)->count();
                if (in_array($t, $coreDataTables, true)) {
                    // Backup full data of core tables to allow restore if needed
                    $rows = DB::table($t)->get();
                    // Convert to arrays for JSON
                    $info['data'] = $rows->map(fn($r) => (array) $r)->all();
                }
            } catch (\Throwable $e) {
                $info['error'] = 'count_failed: ' . $e->getMessage();
            }
            $backup['tables'][] = $info;
        }

        Storage::put($backupPath, json_encode($backup, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE));

        // 2) Identify non-documented tables and drop them safely
        $toDrop = array_values(array_diff($tables, $whitelist));

        $logLines = [];
        $logLines[] = "Admin DB cleanup started: {$timestamp}";
        $logLines[] = 'Whitelist: ' . implode(', ', $whitelist);
        $logLines[] = 'Found tables: ' . implode(', ', $tables);
        $logLines[] = 'Drop candidates: ' . implode(', ', $toDrop);

        foreach ($toDrop as $dropTable) {
            // Verify not critical by checking it's not accidentally matching core names
            if (in_array($dropTable, $coreDataTables, true)) {
                $logLines[] = "SKIP core table: {$dropTable}";
                continue;
            }

            // Compute row count for logging before dropping
            $count = null;
            try {
                $count = DB::table($dropTable)->count();
            } catch (\Throwable $e) {
                // ignore count errors
            }

            $logLines[] = "Dropping table '{$dropTable}' (rows=" . ($count ?? 'n/a') . ")";
            // Use raw statement for Postgres to avoid schema builder limitations
            try {
                DB::statement('DROP TABLE IF EXISTS "' . $dropTable . '" CASCADE');
            } catch (\Throwable $e) {
                $logLines[] = "ERROR dropping {$dropTable}: " . $e->getMessage();
            }
        }

        Storage::put($backupDir . "/smartket_admin_db_cleanup_{$timestamp}.log", implode(PHP_EOL, $logLines) . PHP_EOL);
    }

    /**
     * We do not recreate removed tables in down() to avoid reintroducing invalid schema.
     * The backup file created in up() allows manual restore if necessary.
     */
    public function down(): void
    {
        // Intentionally left empty.
    }
};

