<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\SetupController;
use App\Http\Controllers\Api\PolleriaController;

Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:10,1')
    ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);

Route::middleware('auth:sanctum')->group(function () {
    // Rutas que NO necesitan un tenant o lo manejan de forma especial
    Route::get('/me', [AuthController::class, 'me'])
        ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);
    Route::get('/tenant', [TenantController::class, 'show'])
        ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);
    Route::patch('/tenant', [TenantController::class, 'update'])
        ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);
    Route::post('/audit', [AuditController::class, 'store'])
        ->withoutMiddleware(\Spatie\Multitenancy\Http\Middleware\NeedsTenant::class);

    // --- Rutas que SÍ requieren un tenant activo ---
    Route::middleware([
        \Spatie\Multitenancy\Http\Middleware\NeedsTenant::class,
        \App\Http\Middleware\CheckSubscriptionStatus::class
    ])->group(function () {
        // Endpoint protegido: devuelve productos del tenant
        Route::get('/productos', [ProductoController::class, 'index']);

        // Setup Wizard: Datos fiscales (tenant DB activa)
        Route::get('/setup/fiscal', [SetupController::class, 'fiscalShow']);
        Route::post('/setup/fiscal', [SetupController::class, 'fiscalSave']);

        // Nuevo endpoint para obtener los entitlements
        Route::get('/tenant/entitlements', [TenantController::class, 'getEntitlements']);

        // --- Gestión de Staff (solo para admins) ---
        Route::middleware('can:admin')->group(function () {
            Route::apiResource('staff', \App\Http\Controllers\Api\StaffController::class);
            Route::get('/roles', fn() => \App\Models\Role::all());
            
            Route::get('/modules', [\App\Http\Controllers\Api\ModuleController::class, 'index']);
            Route::post('/tenant/modules', [\App\Http\Controllers\Api\ModuleController::class, 'sync']);
        });

        // --- Módulo Pollería (requiere tenant activo) ---
        Route::get('/polleria/menu', [PolleriaController::class, 'menu']);
        Route::post('/polleria/orders', [PolleriaController::class, 'createOrder']);
        Route::get('/polleria/kitchen/orders', [PolleriaController::class, 'kitchenOrders']);
        Route::put('/polleria/orders/{orderId}/prepared', [PolleriaController::class, 'markOrderPrepared'])
            ->whereNumber('orderId');
        Route::post('/polleria/sales', [PolleriaController::class, 'sales']);

        // Administración Pollería (planes y sucursales)
        Route::get('/polleria/plans', [PolleriaController::class, 'plans']);
        Route::get('/polleria/branches', [PolleriaController::class, 'branches']);
        Route::post('/polleria/plans/activate', [PolleriaController::class, 'activatePlan']);
    });
});
