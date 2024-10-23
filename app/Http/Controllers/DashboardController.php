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
        $authUser = User::find(Auth::user()->id);
        //retrieve tenant
        $tenant_id = session('tenant_id');
        $tenant = Tenant::find($tenant_id);
        $payments = [];
        $trips = [];
        $users = [];
        $projects = [];
        $recentPayments = [];
        $recentTrips = [];

        if ($authUser->isAdminInTenant($tenant_id)) {
            $payments = [
                'name' => 'Payments',
                'value' => $tenant->payments->count()
            ];
            $trips = [
                'name' => 'Trips',
                'value' => $tenant->trips->count()
            ];
            $users = [
                'name' => 'Users',
                'value' => $tenant->users->count()
            ];
            $projects = [
                'name' => 'Projects',
                'value' => $tenant->projects->count()
            ];
            $recentPayments = $tenant->payments()->latest()->limit(5)->get();
            $recentTrips = $tenant->trips()->latest()->limit(5)->get();
        } else {
            $projects = [
                'name' => 'Projects',
                'value' => $authUser->adminProjects->count()
            ];
            $payments = [
                'name' => 'Payments',
                'value' => Payment::whereIn('project_id', $authUser->projects->pluck('id'))->count()
            ];
            $trips = [
                'name' => 'Trips',
                'value' => $authUser->adminTrips()->count() //Trip::whereIn('project_id', $authUser->projects->pluck('id'))->count()
            ];

            $projectIds = $authUser->adminProjects->pluck('id');
            $userIds = DB::table("project_user")->whereIn('project_id', $projectIds)->pluck('user_id');
            $projectUserIds = DB::table("project_user")->whereIn('project_id', $projectIds)->pluck('id');
            $users = [
                'name' => 'Users',
                'value' => User::whereIn('id', $userIds)->count()
            ];

            $recentPayments = Payment::whereIn('project_id', $projectIds)->latest()->limit(5)->get();
            $recentTrips = Trip::whereIn('project_user_id', $projectUserIds)->latest()->limit(5)->get();
        }
        $totals = [$payments, $trips, $users, $projects];
        return Inertia::render('Dashboard2', [
            'totals' => $totals,
            'payments' => $recentPayments,
            'trips' => $recentTrips,
        ]);
    }
}
