<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Hotel\Controllers\Api\RoomApiController;
use App\Modules\School\Http\Controllers\Api\BiometricAttendanceController;
use App\Modules\School\Http\Controllers\Api\Academique\LevelsController;

// Notre ModuleServiceProvider appliquera automatiquement le préfixe "api/hotel"
//Route::get('/rooms', [RoomApiController::class, 'index']);
//Route::post('/rooms', [RoomApiController::class, 'store']);



Route::middleware(['auth:sanctum'])->group(function () {
    // Route personnalisée pour basculer rapidement is_active
    Route::patch('levels/{level}/toggle-active', [LevelsController::class, 'toggleActive'])
        ->name('levels.toggle-active');

    // Route ressource standard API (index, store, show, update, destroy)
    Route::apiResource('levels', LevelsController::class);

    /*Route::apiResource('registrations', RegistrationApiController::class);

    Route::patch('school-classes/{school_class}/toggle-active', [SchoolClassApiController::class, 'toggleActive'])
        ->name('api.school-classes.toggle-active');

    Route::apiResource('school-classes', SchoolClassApiController::class);

    Route::patch('series/{serie}/toggle-active', [SerieApiController::class, 'toggleActive'])
        ->name('api.series.toggle-active');

    Route::apiResource('series', SerieApiController::class);

    Route::patch('rooms/{room}/toggle-active', [RoomApiController::class, 'toggleActive'])
        ->name('api.rooms.toggle-active');

    Route::apiResource('rooms', RoomApiController::class);

    Route::apiResource('users', UserApiController::class);*/
    
});

Route::prefix('v1/attendance')->group(function () {
    // API appelée par le capteur/logiciel de la pointeuse biométrique ou RFID
    Route::post('scan', [BiometricAttendanceController::class, 'handleScan']);
});