<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $tenantId = tenant()?->getTenantKey();
        $sessionTenantId = $request->session()->get(
            'authenticated_tenant_id'
        );

        if (
            !$tenantId ||
            !$sessionTenantId ||
            (string) $sessionTenantId !== (string) $tenantId
        ) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('tenant.home', [
                'tenant' => $tenantId,
            ]);
        }

        return $next($request);
    }
}