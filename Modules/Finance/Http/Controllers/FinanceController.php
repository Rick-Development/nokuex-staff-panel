<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FinanceController extends Controller
{
    public function dashboard()
    {
        return view('finance::dashboard');
    }

    public function transactions()
    {
        return view('finance::transactions.index');
    }

    public function reports()
    {
        return view('finance::reports.index');
    }

    public function reconciliation()
    {
        return view('finance::reconciliation.index');
    }

    public function blusalt()
    {
        return view('finance::blusalt.index');
    }
}