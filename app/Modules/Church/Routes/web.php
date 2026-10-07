<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Hotel\Controllers\RoomController;
use App\Modules\Hotel\Controllers\BookingController;

// Ces routes seront automatiquement préfixées et sécurisées par le middleware tenant
Route::prefix('hotel')->name('hotel.')->group(function () {
    //Route::resource('rooms', RoomController::class);
    //Route::resource('bookings', BookingController::class);
});