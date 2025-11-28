<?php

use Illuminate\Support\Facades\Route;
use Modules\Compliance\Http\Controllers\ComplianceController;

Route::middleware(['web', 'auth:staff'])->prefix('staff/compliance')->name('staff.compliance.')->group(function () {
    Route::get('/', [ComplianceController::class, 'dashboard'])->name('dashboard');
    
    // KYC Reviews
    Route::get('/kyc', [ComplianceController::class, 'kycIndex'])->name('kyc.index');
    Route::get('/kyc/{id}', [ComplianceController::class, 'kycShow'])->name('kyc.show');
    Route::put('/kyc/{id}', [ComplianceController::class, 'kycUpdate'])->name('kyc.update');
    
    // Compliance Flags
    Route::get('/flags', [ComplianceController::class, 'flagsIndex'])->name('flags.index');
    Route::put('/flags/{id}', [ComplianceController::class, 'flagUpdate'])->name('flags.update');
});
