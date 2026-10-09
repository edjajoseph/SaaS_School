<?php

// routes/tenant.php

use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Modules\School\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetTenantRouteDefaults;
use App\Http\Middleware\EnsureTenantSession;

Route::prefix('tenant/{tenant}')
    ->middleware([
        InitializeTenancyByPath::class,
        SetTenantRouteDefaults::class,
        'web',
        EnsureTenantSession::class,
    ])
    ->group(function () {

        // 1. Page d'accueil du tenant
        Route::get('/', function () {
            if (auth()->check()) {
                $tenant = tenant();
                $tenant->loadMissing('solution');

                $solutionCode = strtolower(
                    $tenant->solution->code
                    ?? $tenant->solution->type
                    ?? $tenant->solution->slug
                    ?? $tenant->solution_id
                );


            $tenantId = tenant()->getTenantKey();

            return match ($solutionCode) {
                'school', '3' => redirect()->route('school.dashboard', [
                    'tenant' => $tenantId,
                ]),

                'hotel', '1' => redirect()->route('hotel.dashboard', [
                    'tenant' => $tenantId,
                ]),

                'restaurant', '2' => redirect()->route('restaurant.dashboard', [
                    'tenant' => $tenantId,
                ]),

                default => view('tenants.default_dashboard'),
            };
            }

            return view('School::welcome');
        })->name('tenant.home');

        // 2. Chargement des routes des modules
        $modules = ['School', 'Hotel', 'Restaurant'];

        foreach ($modules as $module) {
            $routeFile = app_path("Modules/{$module}/Routes/web.php");

            if (file_exists($routeFile)) {
                require $routeFile;
            }
        }

        // 3. Déconnexion du tenant
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('tenant.logout');
});
