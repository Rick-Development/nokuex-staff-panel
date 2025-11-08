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
    require base_path('Modules/Core/Routes/web.php');
    require base_path('Modules/Chat/Routes/web.php');
    require base_path('Modules/CustomerCare/Routes/web.php');
    require base_path('Modules/Sales/Routes/web.php');
    require base_path('Modules/Finance/Routes/web.php');
    require base_path('Modules/Compliance/Routes/web.php');
});