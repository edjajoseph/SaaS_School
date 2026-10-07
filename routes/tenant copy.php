<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // 1. Route d'accueil du tenant
    Route::get('/', function () {
        $tenant = tenant();
        if (!$tenant) {
            return response('Tenant non trouvé', 404);
        }

        $tenant->loadMissing('solution');

        $appType = $tenant->solution->code 
                ?? $tenant->solution->type 
                ?? $tenant->solution->slug 
                ?? $tenant->solution_id;

        return match ($appType) {
            'hotel', 1      => view('tenants.hotel.dashboard'),
            'school', 3     => view('tenants.school.dashboard'),
            'restaurant', 2 => view('tenants.restaurant.dashboard'),
            default         => view('tenants.default_dashboard'),
        };
    })->name('tenant.home');

    // 2. Inclusion sécurisée des sous-fichiers de routes
    $tenantFiles = [
        __DIR__ . '/tenants/hotel.php',
        __DIR__ . '/tenants/school.php',
        __DIR__ . '/tenants/restaurant.php',
    ];

    foreach ($tenantFiles as $file) {
        if (file_exists($file)) {
            require $file;
        }
    }
});