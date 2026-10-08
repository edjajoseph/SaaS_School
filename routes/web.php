<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Importation des contrôleurs
use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\FounderDashboardController;
use App\Http\Controllers\Central\SolutionsController;
use App\Http\Controllers\Central\PlansController;
use App\Http\Controllers\Central\TenantsController;
use App\Http\Controllers\Central\TenantRegistrationsController;
use App\Http\Controllers\Central\SubscriptionsController;
use App\Http\Controllers\Central\InvoicesController;
use App\Http\Controllers\Central\PaymentsController;
use App\Http\Controllers\Central\MobilePaymentWebhookController;
use App\Http\Controllers\Compte\RoleController;
use App\Http\Controllers\Compte\UserController;
use App\Http\Controllers\Compte\PermissionController;

// =============================================================
// ROUTES CENTRALES
// =============================================================

// A. ROUTES PUBLIQUES CENTRALES
Route::middleware(['web'])->group(function () {

    Route::get('/activate-account/{user}', [AccountActivationController::class, 'showActivationForm'])
        ->name('account.activate')
        ->middleware('signed');

    Route::post('/activate-account/{user}', [AccountActivationController::class, 'activate'])
        ->name('account.activate.submit');

    Auth::routes();

    // Page d'accueil centrale : une route distincte par domaine central.
    $centralDomains = config('tenancy.central_domains', []);

    foreach ($centralDomains as $index => $domain) {
        Route::domain($domain)->get('/', function () {
            return view('accueil');
        })->name($index === 0 ? 'landing' : "landing.central.{$index}");
    }

    Route::prefix('webhooks/payments')->group(function () {
        Route::post('/wave', [MobilePaymentWebhookController::class, 'handleWave']);
        Route::post('/cinetpay', [MobilePaymentWebhookController::class, 'handleCinetPay']);
        Route::post('/orange-money', [MobilePaymentWebhookController::class, 'handleOrangeMoney']);
    });
});

// B. ESPACE D'ADMINISTRATION CENTRAL
Route::middleware(['web', 'auth', 'role:fondateur|administrateur'])->group(function () {

    Route::get('/founder', [FounderDashboardController::class, 'index'])->name('founder');

    Route::resource('solutions', SolutionsController::class);
    Route::resource('plans', PlansController::class);
    Route::resource('tenants', TenantsController::class);
    //Route::post('/register-tenant', [TenantRegistrationsController::class, 'register'])->name('tenants.register');

    Route::resource('subscriptions', SubscriptionsController::class);
    Route::resource('invoices', InvoicesController::class);
    Route::resource('payments', PaymentsController::class);

    Route::post(
        'invoices/{invoice}/payments',
        [PaymentsController::class, 'store']
    )->name('invoices.payments.store');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);

    Route::patch(
        '/users/{user}/toggle-status',
        [UserController::class, 'toggleStatus']
    )->name('users.toggle-status');

    Route::post(
        '/users/{user}/reset-password',
        [UserController::class, 'resetPassword']
    )->name('users.reset-password');
});