<?php

// routes/tenant.php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Modules\School\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    PreventAccessFromCentralDomains::class,
    InitializeTenancyByDomain::class,
    'web',
])->group(function () {

    // 1. Page d'accueil / Landing page du Tenant
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

            // Rediriger proprement au lieu d'appeler le contrôleur directement 
            return match ($solutionCode) {
                'school', '3'    => redirect()->route('school.dashboard'),
                'hotel', '1'     => redirect()->route('hotel.dashboard'),
                'restaurant', '2'=> redirect()->route('restaurant.dashboard'),
                default          => view('tenants.default_dashboard'),
            };
        }

        return view('School::welcome'); 
    })->name('tenant.home');

    // 2. Chargement des modules
    $modules = ['School', 'Hotel', 'Restaurant'];
    foreach ($modules as $module) {
        $routeFile = app_path("Modules/{$module}/Routes/web.php");
        if (file_exists($routeFile)) {
            require $routeFile;
        }
    }

    // Déconnexion propre au tenant
    Route::post('/logout', [LoginController::class, 'logout'])->name('tenant.logout');
});