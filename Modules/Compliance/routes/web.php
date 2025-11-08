<?php

use Illuminate\Support\Facades\Route;
use Modules\Compliance\Http\Controllers\ComplianceController;

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/compliance/dashboard', [ComplianceController::class, 'dashboard'])->name('compliance.dashboard');
    Route::get('/compliance/freeze', [ComplianceController::class, 'freeze'])->name('compliance.freeze');
    Route::get('/compliance/otc', [ComplianceController::class, 'otc'])->name('compliance.otc');
    Route::get('/compliance/kyc', [ComplianceController::class, 'kyc'])->name('compliance.kyc');
    Route::get('/compliance/kyb', [ComplianceController::class, 'kyb'])->name('compliance.kyb');
    Route::get('/compliance/flagging', [ComplianceController::class, 'flagging'])->name('compliance.flagging');
});