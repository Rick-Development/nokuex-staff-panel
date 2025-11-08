<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerCare\Http\Controllers\CustomerCareController;
use Modules\CustomerCare\Http\Controllers\CustomerController;
use Modules\CustomerCare\Http\Controllers\SupportTicketController;

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/customercare/dashboard', [CustomerCareController::class, 'dashboard'])->name('customercare.dashboard');
    
    // CRM Routes
    Route::get('/customercare/crm', [CustomerController::class, 'index'])->name('customercare.crm');
    Route::get('/customercare/crm/create', [CustomerController::class, 'create'])->name('customercare.crm.create');
    Route::post('/customercare/crm', [CustomerController::class, 'store'])->name('customercare.crm.store');
    Route::get('/customercare/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('customercare.crm.edit');
    Route::put('/customercare/customer/{customer}', [CustomerController::class, 'update'])->name('customercare.crm.update');
    Route::get('/customercare/customer/{customer}', [CustomerController::class, 'show'])->name('customercare.crm.show');
    
    // Support Tickets Routes
    Route::get('/customercare/tickets', [SupportTicketController::class, 'index'])->name('customercare.tickets');
    Route::post('/customercare/tickets', [SupportTicketController::class, 'store'])->name('customercare.tickets.store');
    Route::put('/customercare/tickets/{ticket}', [SupportTicketController::class, 'update'])->name('customercare.tickets.update');
    Route::get('/customercare/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('customercare.tickets.show');
    
    // Disputes Routes (to be implemented)
    Route::get('/customercare/disputes', [CustomerCareController::class, 'disputes'])->name('customercare.disputes');
});