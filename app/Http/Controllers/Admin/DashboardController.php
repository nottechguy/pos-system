<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Business;

class DashboardController extends Controller {
    public function index() {

        $business = Business::firstOrFail(); 
        // For now, return the base dashboard view.
        // Later, we can pass metrics or KPIs here.
        return view('admin.dashboard.index');
    }
}