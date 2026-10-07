<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('hotel.welcome'); // Ou la landing page spécifique de l'hôtel
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\Grh\DashboardController::class, 'index'])
         ->name('tenant.dashboard');

    // Routes métiers Hôtel
    Route::resource('rooms', App\Http\Controllers\Grh\RoomController::class);
    Route::resource('bookings', App\Http\Controllers\Grh\BookingController::class);
});