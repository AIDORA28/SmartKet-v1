<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Tenant;

class DiagnoseAuth extends Command
{
    protected $signature = 'diagnose:auth {--email=} {--password=} {--db-name=}';
    protected $description = 'Diagnostica usuario, tenant y relación tenant_user; verifica login y tenantId calculado';

    public function handle(): int
    {
        $email = (string)($this->option('email') ?? '');
        $password = (string)($this->option('password') ?? '');

        $this->info('--- SmartKet Auth Diagnosis ---');
        $this->line('Connection: '.config('database.default'));
        $this->line('Driver: '.DB::getDriverName());

        // Enumerate key tables existence
        $tables = ['users','tenants','tenant_user','personal_access_tokens'];
        foreach ($tables as $t) {
            $this->line(sprintf('Table %-24s: %s', $t, Schema::hasTable($t) ? 'YES' : 'NO'));
        }

        if ($email === '') {
            $this->warn('No email provided. Use --email=...');
            return self::FAILURE;
        }

        /** @var User|null $user */
        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error('User not found: '.$email);
            return self::FAILURE;
        }
        $this->info('User: id='.$user->id.' email='.$user->email.' name='.$user->name);

        // Check password if provided
        if ($password !== '') {
            $this->line('Password provided: checking...');
            $this->line(Hash::check($password, $user->password) ? 'Password OK' : 'Password INVALID');
        } else {
            $this->warn('Password not provided; skipping password check.');
        }

        // Inspect tenant by provided db-name or heuristic
        $dbName = (string)($this->option('db-name') ?? '');
        if ($dbName !== '') {
            $tenant = Tenant::where('db_name', $dbName)->first();
            $this->line('Probe tenant by db_name: '.($tenant ? ('id='.$tenant->id.' business_name='.$tenant->business_name) : 'NONE'));
        } else {
            $tenant = Tenant::where('business_name', 'ILIKE', '%polleria%')->latest('id')->first();
            $this->line('Probe tenant by business_name like polleria: '.($tenant ? ('id='.$tenant->id.' db_name='.$tenant->db_name) : 'NONE'));
        }

        // Pivot relations
        $rowsByUser = DB::table('tenant_user')->where('user_id', $user->id)->get(['tenant_id','user_id']);
        $this->line('tenant_user rows for user: '.json_encode($rowsByUser->toArray()));

        $tenantIdFromRelation = $user->tenants()->select('id')->pluck('id')->first();
        $this->info('tenantId via relation: '.json_encode($tenantIdFromRelation));

        // Final verdict on login response shape
        $loginResponse = [
            'ok' => true,
            'token' => '<not-generated-here>',
            'tenant_id' => $tenantIdFromRelation !== null ? (int)$tenantIdFromRelation : null,
            'tenantId' => $tenantIdFromRelation !== null ? (int)$tenantIdFromRelation : null,
        ];
        $this->line('Simulated /api/login payload keys: '.json_encode(array_keys($loginResponse)));
        $this->line('Simulated /api/login tenantId: '.json_encode($loginResponse['tenantId']));

        if ($loginResponse['tenantId'] === null) {
            $this->error('Diagnosis: tenantId is NULL → Falta relación en tenant_user para el usuario.');
            $this->line('Fix: asociar el usuario al tenant correcto (user->tenants()->attach(<tenant_id>)).');
        } else {
            $this->info('Diagnosis: tenantId OK (non-null). Frontend debe aceptar el login.');
        }

        return self::SUCCESS;
    }
}
