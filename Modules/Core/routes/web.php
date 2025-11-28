<?php

use Modules\Core\Http\Controllers\AuthController;
use Modules\Core\Http\Controllers\CoreController;

Route::group(['middleware' => 'web', 'prefix' => 'staff', 'as' => 'core.'], function () {
    // Guest routes
    Route::middleware('guest:staff')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('auth:staff')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [CoreController::class, 'index'])->name('dashboard');
    });
});
