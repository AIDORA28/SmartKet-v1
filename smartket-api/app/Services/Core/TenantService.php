<?php

namespace App\Services\Core;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SystemEvent;
use App\Models\Core\Tenant;
use App\Models\Core\User;
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
    public function createTenant(string $businessName, string $emailOwner, string $passOwner, string $businessType): Tenant
    {
        try {
            $trialPlan = $this->getTrialPlan();
            $user = $this->firstOrCreateUser($businessName, $emailOwner, $passOwner);
            $dbConfig = $this->createPostgresDatabase($businessName);
            
            $tenant = $this->registerTenantInLandlordDb($businessName, $businessType, $dbConfig, $trialPlan, $user);

            $this->provisionTenantDatabase($tenant);

            SystemEvent::create([
                'event_type' => 'tenant.created',
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'metadata' => ['plan' => 'trial', 'business_type' => $businessType],
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

    private function createPostgresDatabase(string $businessName): array
    {
        $slug = Str::slug($businessName);
        $suffix = Str::lower(Str::random(4));
        $dbName = 'smartket_' . $slug . '_' . $suffix;
        $dbUser = 'user_' . $slug . '_' . $suffix;
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

    private function registerTenantInLandlordDb(string $businessName, string $businessType, array $dbConfig, Plan $trialPlan, User $user): Tenant
    {
        return DB::transaction(function () use ($businessName, $businessType, $dbConfig, $trialPlan, $user) {
            $slug = $this->generateUniqueSlug($businessName);

            $tenant = Tenant::create([
                'business_name' => $businessName,
                'slug' => $slug,
                'business_type' => $businessType,
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
            // 1. Crear sucursal principal
            DB::connection('tenant')->table('branches')->insert(['name' => 'Principal', 'created_at' => now(), 'updated_at' => now()]);

            // 2. Aprovisionar Roles y Permisos según el rubro (Fase 3.2)
            $rbacService = app(\App\Services\Core\RolePermissionService::class);
            $rbacService->provisionForVertical($tenant->business_type);
        });
        Log::info("TenantService: Sucursal principal y Roles/Permisos ({$tenant->business_type}) aprovisionados.");

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

    /**
     * Obtiene los entitlements (permisos y límites) para un tenant.
     */
    public function getEntitlements(Tenant $tenant): array
    {
        $tenant->load(['plan', 'modules']);

        $entitlements = [
            'seats' => [],
            'modules' => [],
        ];

        $allModules = \App\Models\Core\Module::all();
        $purchasedModules = $tenant->modules->keyBy('identifier');

        $staffRolesCount = $tenant->execute(function() {
            $counts = [];
            $staffWithRoles = \App\Models\Core\Staff::with('roles')->get();

            foreach ($staffWithRoles as $staffMember) {
                foreach ($staffMember->roles as $role) {
                    if ($role instanceof \App\Models\Core\Role) {
                        if (!isset($counts[$role->name])) {
                            $counts[$role->name] = 0;
                        }
                        $counts[$role->name]++;
                    }
                }
            }
            return collect($counts);
        });

        foreach ($allModules as $module) {
            if ($module->type === 'seat') {
                $identifier = $module->identifier;
                $roleName = str_replace('seat_', '', $identifier);
                $purchasedModule = $purchasedModules->get($identifier);

                $entitlements['seats'][] = [
                    'identifier' => $identifier,
                    'name' => $module->name,
                    'limit' => $purchasedModule ? $purchasedModule->pivot->quantity : 0,
                    'current' => $staffRolesCount->get($roleName, 0),
                ];
            } else {
                $entitlements['modules'][] = [
                    'identifier' => $module->identifier,
                    'name' => $module->name,
                    'enabled' => $purchasedModules->has($module->identifier),
                ];
            }
        }

        return $entitlements;
    }
}