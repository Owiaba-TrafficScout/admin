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
    public function index()
    {

        //retrieve tenant
        $project = Project::find(session('project_id'));
        Log::info($project->name);
        $trips = [
            'name' => 'Trips',
            'value' => $project->trips->count()
        ];
        $users = [
            'name' => 'Users',
            'value' => $project->users->count()
        ];
        $recentTrips = $project->trips()->latest()->limit(5)->get();

        $totals = [$trips, $users];
        return Inertia::render('Dashboard2', [
            'totals' => $totals,
            'trips' => $recentTrips,
        ]);
    }
}
