<?php

namespace App\Providers;

use App\Models\Core\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            if ($user instanceof User) {
                return true; // El dueño del tenant siempre es admin
            }
            return $user->roles->pluck('name')->contains('admin');
        });
    }
}
