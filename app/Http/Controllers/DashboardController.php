<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        //retrieve tenant
        $project = Project::find($request->user()?->state?->project_id);
        $trips = [
            'name' => 'Trips',
            'value' => $project?->trips->count() ?? 0
        ];
        $users = [
            'name' => 'Users',
            'value' => $project?->users->count() ?? 0
        ];
        $recentTrips = $project?->trips()->latest()->limit(5)->get() ?? [];

        $totals = [$trips, $users];
        return Inertia::render('Dashboard2', [
            'totals' => $totals,
            'trips' => $recentTrips,
        ]);
    }
}
