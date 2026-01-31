<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SystemEvent;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantService
{
    /**
     * Orquesta la creación completa de un nuevo tenant.
     */
    public function createTenant(string $nombreNegocio, string $emailDueno, string $passDueno, string $rubro): Tenant
    {
        try {
            $trialPlan = $this->getTrialPlan();
            $user = $this->firstOrCreateUser($nombreNegocio, $emailDueno, $passDueno);
            $dbConfig = $this->createPostgresDatabase($nombreNegocio);
            
            $tenant = $this->registerTenantInLandlordDb($nombreNegocio, $rubro, $dbConfig, $trialPlan, $user);

            $this->provisionTenantDatabase($tenant);

            SystemEvent::create([
                'event_type' => 'tenant.created',
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'metadata' => ['plan' => 'trial', 'rubro' => $rubro],
            ]);

            Log::info("TenantService: Proceso completado para Tenant ID: {$tenant->id}.");

            return $tenant;

        } catch (\Exception $e) {
            Log::error("TenantService: Fallo catastrófico durante la creación del tenant. Error: " . $e->getMessage());
            // Aquí se podría añadir lógica de "rollback" manual (ej. eliminar la DB creada).
            throw $e;
        }
    }

    private function getTrialPlan(): Plan
    {
        $trialPlan = Plan::where('is_trial', true)->first();
        if (!$trialPlan) {
            throw new \Exception('Plan "Trial" no encontrado. Ejecuta el PlanSeeder primero.');
        }
        Log::info("TenantService: Plan Trial encontrado (ID: {$trialPlan->id}).");
        return $trialPlan;
    }

    private function firstOrCreateUser(string $name, string $email, string $password): User
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password)]
        );
        Log::info("TenantService: Usuario dueño creado/obtenido (ID: {$user->id}).");
        return $user;
    }

    private function createPostgresDatabase(string $nombreNegocio): array
    {
        $dbName = 'smartket_' . Str::lower(Str::random(12));
        $dbUser = 'user_' . Str::lower(Str::random(12));
        $dbPasswordPlain = Str::random(16);

        Log::info("TenantService: Creando DB '$dbName' y usuario '$dbUser'.");
        $pdo = DB::connection('pgsql')->getPdo();
        $pdo->exec("CREATE DATABASE \"$dbName\"");
        $pdo->exec("CREATE USER \"$dbUser\" WITH PASSWORD '$dbPasswordPlain'");
        $pdo->exec("GRANT ALL PRIVILEGES ON DATABASE \"$dbName\" TO \"$dbUser\"");
        $pdo->exec("ALTER DATABASE \"$dbName\" OWNER TO \"$dbUser\"");
        Log::info("TenantService: Privilegios de DB asignados.");

        return [
            'db_name' => $dbName,
            'db_user' => $dbUser,
            'db_password' => $dbPasswordPlain,
        ];
    }

    private function registerTenantInLandlordDb(string $nombreNegocio, string $rubro, array $dbConfig, Plan $trialPlan, User $user): Tenant
    {
        return DB::transaction(function () use ($nombreNegocio, $rubro, $dbConfig, $trialPlan, $user) {
            $slug = $this->generateUniqueSlug($nombreNegocio);

            $tenant = Tenant::create([
                'nombre_negocio' => $nombreNegocio,
                'slug' => $slug,
                'rubro' => $rubro,
                'db_name' => $dbConfig['db_name'],
                'db_user' => $dbConfig['db_user'],
                'db_password' => encrypt($dbConfig['db_password']),
                'setup_complete' => false,
            ]);
            Log::info("TenantService: Tenant registrado (ID: {$tenant->id}) con slug '$slug'.");

            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $trialPlan->id,
                'status' => 'trialing',
                'trial_ends_at' => Carbon::now()->addDays(14),
            ]);
            Log::info("TenantService: Suscripción Trial creada para Tenant ID: {$tenant->id}.");

            $user->tenants()->attach($tenant->id);
            Log::info("TenantService: Usuario y tenant vinculados.");

            return $tenant;
        });
    }
    
    private function provisionTenantDatabase(Tenant $tenant): void
    {
        $tenant->makeCurrent();
        Log::info("TenantService: Tenant actual establecido a ID: {$tenant->id}.");

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path'     => 'database/migrations/tenant',
            '--force'    => true,
        ]);
        Log::info("TenantService: Migraciones de tenant ejecutadas.");

        $tenant->execute(function ($tenant) {
            DB::connection('tenant')->table('branches')->insert(['name' => 'Principal', 'created_at' => now(), 'updated_at' => now()]);
        });
        Log::info("TenantService: Primera sucursal 'Principal' creada.");

        $tenant->forgetCurrent();
        Log::info("TenantService: Tenant actual olvidado.");
    }

    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        return $slug;
    }
}