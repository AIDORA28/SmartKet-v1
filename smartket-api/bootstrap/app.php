<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Habilitar CORS globalmente para API y SPA
        $middleware->use([\Illuminate\Http\Middleware\HandleCors::class]);

        // Middleware para leer el token desde la cookie HttpOnly
        $middleware->prepend(\App\Http\Middleware\AuthenticateWithCookie::class);

        // Auditoría Global (ISO 27001)
        $middleware->append(\App\Http\Middleware\AuditMiddleware::class);

        // Alias para RBAC Granular
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
