<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerCare\Http\Controllers\CustomerCareController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('customercares', CustomerCareController::class)->names('customercare');
});
