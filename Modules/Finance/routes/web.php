<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/finance/dashboard', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
    Route::get('/finance/transactions', [FinanceController::class, 'transactions'])->name('finance.transactions');
    Route::get('/finance/reports', [FinanceController::class, 'reports'])->name('finance.reports');
    Route::get('/finance/reconciliation', [FinanceController::class, 'reconciliation'])->name('finance.reconciliation');
    Route::get('/finance/blusalt', [FinanceController::class, 'blusalt'])->name('finance.blusalt');
});