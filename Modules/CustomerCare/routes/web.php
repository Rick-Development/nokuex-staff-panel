<?php

use Modules\CustomerCare\Http\Controllers\CustomerController;

Route::group(['middleware' => ['web', 'auth:staff'], 'prefix' => 'staff/customers', 'as' => 'staff.customers.'], function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
    Route::post('/{id}/status', [CustomerController::class, 'updateStatus'])->name('update-status');
});

use Modules\CustomerCare\Http\Controllers\SupportTicketController;

Route::group(['middleware' => ['web', 'auth:staff'], 'prefix' => 'staff/tickets', 'as' => 'staff.tickets.'], function () {
    Route::get('/', [SupportTicketController::class, 'index'])->name('index');
    Route::get('/{id}', [SupportTicketController::class, 'show'])->name('show');
    Route::post('/{id}/reply', [SupportTicketController::class, 'reply'])->name('reply');
    Route::put('/{id}/update', [SupportTicketController::class, 'update'])->name('update');
});

use Modules\CustomerCare\Http\Controllers\DisputeController;

Route::group(['middleware' => ['web', 'auth:staff'], 'prefix' => 'staff/disputes', 'as' => 'staff.disputes.'], function () {
    Route::get('/', [DisputeController::class, 'index'])->name('index');
    Route::get('/{id}', [DisputeController::class, 'show'])->name('show');
    Route::put('/{id}/update', [DisputeController::class, 'update'])->name('update');
});
