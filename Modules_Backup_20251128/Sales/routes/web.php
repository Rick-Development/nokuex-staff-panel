<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/sales/dashboard', [SalesController::class, 'dashboard'])->name('sales.dashboard');
    Route::get('/sales/leads', [SalesController::class, 'leads'])->name('sales.leads');
    Route::get('/sales/performance', [SalesController::class, 'performance'])->name('sales.performance');
    Route::get('/sales/followups', [SalesController::class, 'followups'])->name('sales.followups');
});