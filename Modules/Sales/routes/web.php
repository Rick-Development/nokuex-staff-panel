<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;

Route::middleware(['web', 'auth:staff'])->prefix('staff/sales')->name('staff.sales.')->group(function () {
    Route::get('/', [SalesController::class, 'dashboard'])->name('dashboard');
    Route::get('/leads', [SalesController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [SalesController::class, 'create'])->name('leads.create');
    Route::post('/leads', [SalesController::class, 'store'])->name('leads.store');
    Route::get('/leads/{id}', [SalesController::class, 'show'])->name('leads.show');
    Route::get('/leads/{id}/edit', [SalesController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{id}', [SalesController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{id}', [SalesController::class, 'destroy'])->name('leads.destroy');
});

