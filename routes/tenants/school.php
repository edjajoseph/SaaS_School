<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('school.welcome');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\School\DashboardController::class, 'index'])
         ->name('tenant.dashboard');

    // Routes métiers École
    Route::resource('students', App\Http\Controllers\School\StudentController::class);
    Route::resource('courses', App\Http\Controllers\School\CourseController::class);
});