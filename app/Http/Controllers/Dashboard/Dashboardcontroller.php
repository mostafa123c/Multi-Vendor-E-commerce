<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class Dashboardcontroller extends Controller
{
    public function index()
    {
        return view('Dashboard.index');
    }
}
