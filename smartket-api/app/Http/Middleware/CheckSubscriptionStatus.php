<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::current();

        if ($tenant && $tenant->plan && $tenant->plan->is_trial && $tenant->trial_ends_at->isPast()) {
            // Si el trial ha expirado, denegar el acceso.
            return response()->json(['message' => 'Tu período de prueba ha terminado. Por favor, suscríbete a un plan.'], 402); // 402 Payment Required
        }

        return $next($request);
    }
}
