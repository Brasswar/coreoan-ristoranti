<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here are the authentication routes for the restaurant reservation system.
| These routes handle login and logout functionality.
|
*/

// Guest routes (accessible only when not authenticated)
Route::middleware('guest')->group(function () {
    // Show login form
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Handle login submission
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated routes (accessible only when authenticated)
Route::middleware('auth')->group(function () {
    // Handle logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
