<?php

use App\Http\Controllers\Central\InvoiceController;
use App\Http\Controllers\Central\PaymentController;
use App\Http\Controllers\Central\PlanController;
use App\Http\Controllers\Central\SolutionController;
use App\Http\Controllers\Central\SubscriptionController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\Central\TenantRegistrationController;
use App\Http\Controllers\Central\MobilePaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/central')->group(function () {
    
    // Inscription publique
    Route::post('/register', [TenantRegistrationController::class, 'register']);

    // Gestion de l'Administration Centrale
    Route::apiResource('solutions', SolutionController::class);
    Route::apiResource('plans', PlanController::class);

    // Tenants
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::get('/tenants/{tenant}', [TenantController::class, 'show']);
    Route::patch('/tenants/{tenant}/status', [TenantController::class, 'updateStatus']);
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy']);

    // Subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show']);
    Route::post('/subscriptions/{subscription}/change-plan', [SubscriptionController::class, 'changePlan']);
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);

    // Invoices & Payments
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::post('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid']);
    
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
});

Route::prefix('webhooks/payments')->group(function () {
    Route::post('/wave', [MobilePaymentWebhookController::class, 'handleWave']);
    Route::post('/cinetpay', [MobilePaymentWebhookController::class, 'handleCinetPay']);
    Route::post('/orange-money', [MobilePaymentWebhookController::class, 'handleOrangeMoney']);
});