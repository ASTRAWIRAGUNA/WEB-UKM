<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardUkmController extends Controller
{
    public function index()
    {
        return view('bph.dashboardBPH');
    }
}
