<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SalesController extends Controller
{
    public function dashboard()
    {
        return view('sales::dashboard');
    }

    public function leads()
    {
        return view('sales::leads.index');
    }

    public function performance()
    {
        return view('sales::performance.index');
    }

    public function followups()
    {
        return view('sales::followups.index');
    }
}