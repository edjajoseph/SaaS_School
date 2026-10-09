<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetTenantRouteDefaults
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentTenant = tenant();

        if ($currentTenant) {
            URL::defaults([
                'tenant' => $currentTenant->getTenantKey(),
            ]);
        }

        return $next($request);
    }
}