<?php

namespace Modules\CustomerCare\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerCareController extends Controller
{
    public function dashboard()
    {
        return view('customercare::dashboard');
    }

    public function crm()
    {
        return view('customercare::crm.index');
    }

    public function tickets()
    {
        return view('customercare::tickets.index');
    }

    public function disputes()
    {
        return view('customercare::disputes.index');
    }
}