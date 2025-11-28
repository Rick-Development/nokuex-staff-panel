<?php

use Modules\Finance\Http\Controllers\FinanceController;

Route::group(['middleware' => ['web', 'auth:staff'], 'prefix' => 'staff/finance', 'as' => 'staff.finance.'], function () {
    Route::get('/', [FinanceController::class, 'dashboard'])->name('dashboard');
    Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/{id}', [FinanceController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/export/csv', [FinanceController::class, 'export'])->name('transactions.export');
    Route::post('/transactions/import/csv', [FinanceController::class, 'import'])->name('transactions.import');
    Route::get('/reconciliation', [FinanceController::class, 'reconciliation'])->name('reconciliation');
    Route::post('/reconciliation/run', [FinanceController::class, 'runReconciliation'])->name('reconciliation.run');
    Route::post('/reconciliation/create', [FinanceController::class, 'createReconciliation'])->name('reconciliation.create');
    Route::post('/reconciliation/{id}/update-status', [FinanceController::class, 'updateReconciliationStatus'])->name('reconciliation.update-status');
});
