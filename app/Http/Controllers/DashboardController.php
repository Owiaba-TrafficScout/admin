<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
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
        $totals = [$payments, $trips, $users, $project];
        $recentPayments = Payment::latest()->limit(5)->get();
        $recentTrips = Trip::latest()->limit(5)->get();
        return Inertia::render('Dashboard2', [
            'totals' => $totals,
            'payments' => $recentPayments,
            'trips' => $recentTrips,
        ]);
    }
}
