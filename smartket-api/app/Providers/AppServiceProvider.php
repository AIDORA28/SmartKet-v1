<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define el rate limiter 'api' utilizado por 'throttle:api'
        RateLimiter::for('api', function (Request $request) {
            return [
                Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip()),
            ];
        });

        // Automatización: Crear DB si no existe al migrar (Fase 3: Proactivo)
        // Solo si estamos genuinamente en la consola y no en un Artisan::call desde la web
        if (app()->runningInConsole() && !isset($_SERVER['HTTP_HOST'])) {
            \Illuminate\Support\Facades\Event::listen(
                \Illuminate\Console\Events\CommandStarting::class,
                function (\Illuminate\Console\Events\CommandStarting $event) {
                    if (in_array($event->command, ['migrate', 'migrate:fresh', 'migrate:install'])) {
                        \Illuminate\Support\Facades\Artisan::call('db:create');
                    }
                }
            );
        }
    }
}
