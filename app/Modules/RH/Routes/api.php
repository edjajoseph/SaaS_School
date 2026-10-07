<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Hotel\Controllers\Api\RoomApiController;

// Notre ModuleServiceProvider appliquera automatiquement le préfixe "api/hotel"
//Route::get('/rooms', [RoomApiController::class, 'index']);
//Route::post('/rooms', [RoomApiController::class, 'store']);