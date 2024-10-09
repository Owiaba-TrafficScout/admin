<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $payments = [
            'name' => 'Payments',
            'value' => Payment::count()
        ];
        $trips = [
            'name' => 'Trips',
            'value' => Trip::count()
        ];
        $users = [
            'name' => 'Users',
            'value' => User::count()
        ];
        $project = [
            'name' => 'Projects',
            'value' => Project::count()
        ];
        $recentPayments = Payment::latest()->limit(5)->get();
        $recentTrips = Trip::latest()->limit(5)->get();
        if (auth()->user()->isProjectAdmin()) {
            $project = [
                'name' => 'Projects',
                'value' => auth()->user()->projects->count()
            ];
            $payments = [
                'name' => 'Payments',
                'value' => Payment::whereIn('project_id', auth()->user()->projects->pluck('id'))->count()
            ];
            $trips = [
                'name' => 'Trips',
                'value' => Trip::whereIn('project_id', auth()->user()->projects->pluck('id'))->count()
            ];

            $projectIds = auth()->user()->projects->pluck('id');
            $userIds = DB::table("project_user")->whereIn('project_id', $projectIds)->pluck('user_id');
            $users = [
                'name' => 'Users',
                'value' => User::whereIn('id', $userIds)->count()
            ];

            $recentPayments = Payment::whereIn('project_id', $projectIds)->latest()->limit(5)->get();
            $recentTrips = Trip::whereIn('project_id', $projectIds)->latest()->limit(5)->get();
        }
        $totals = [$payments, $trips, $users, $project];
        return Inertia::render('Dashboard2', [
            'totals' => $totals,
            'payments' => $recentPayments,
            'trips' => $recentTrips,
        ]);
    }
}
