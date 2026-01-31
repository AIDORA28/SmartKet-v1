<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanObsoleteData extends Command
{
    protected $signature = 'smartket:clean-obsolete {--days=90 : Días de retención para auditoría}';
    protected $description = 'Elimina datos obsoletos (auditoría) para optimizar recursos del tenant';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $threshold = Carbon::now()->subDays($days);

        try {
            $deleted = DB::table('audit_events')
                ->where('created_at', '<', $threshold)
                ->delete();
            Log::info('cleanup.audit_events', ['deleted' => $deleted, 'threshold' => $threshold->toDateTimeString()]);
            $this->info("Audit events eliminados: {$deleted}");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('cleanup.audit_events.error', ['error' => $e->getMessage()]);
            $this->error('Error eliminando eventos de auditoría: '.$e->getMessage());
            return Command::FAILURE;
        }
    }
}

