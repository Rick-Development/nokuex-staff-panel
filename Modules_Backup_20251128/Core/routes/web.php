<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AuthController;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\StaffController;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\NotificationController;

// Public Authentication Routes (no prefix)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('core.login');
Route::post('/login', [AuthController::class, 'login'])->name('core.login.post');

// Protected Routes with core prefix
Route::prefix('core')->middleware(['auth:staff'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('core.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('core.logout');

    // Staff Management
    Route::get('/staff/data', [StaffController::class, 'getData'])->name('core.staff.data');
    Route::resource('staff', StaffController::class)->names('core.staff');

    // Role Management
    Route::get('/role/data', [RoleController::class, 'getData'])->name('core.role.data');
    Route::resource('role', RoleController::class)->names('core.role');

    // Notification Management
    Route::get('/notification/data', [NotificationController::class, 'getData'])->name('core.notification.data');
    Route::post('/notification/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('core.notification.mark-read');
    Route::resource('notification', NotificationController::class)->names('core.notification');
});
