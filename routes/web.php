<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('core.login');
});

Route::get('/login', function () {
    return redirect()->route('core.login');
});

Route::get('/dashboard', function () {
    return redirect()->route('core.dashboard');
});

// Include all module routes
Route::group([], function () {
    require base_path('Modules/Core/routes/web.php');
    require base_path('Modules/Chat/routes/web.php');
    require base_path('Modules/CustomerCare/routes/web.php');
    require base_path('Modules/Sales/routes/web.php');
    require base_path('Modules/Finance/routes/web.php');
    require base_path('Modules/Compliance/routes/web.php');
});