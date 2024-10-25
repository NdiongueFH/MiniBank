<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function clientDashboard()
    {
        return view('dashboards.dashboardClient');
    }

    public function agentDashboard()
    {
        return view('dashboards.dashboardAgent');
    }

    public function distributeurDashboard()
    {
        return view('dashboards.dashboardDistributeur');
    }
}

