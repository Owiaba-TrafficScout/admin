<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use App\Models\TripStop;
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
        $trips = [
            'name' => 'Trips',
            'value' => $project?->trips->count() ?? 0
        ];
        $users = [
            'name' => 'Users',
            'value' => $project?->users->count() ?? 0
        ];
        $tripStops = [
            'name' => 'Trip Stops',
            'value' => $project->trips()->leftjoin('trip_stops', 'trips.id', '=', 'trip_stops.trip_id')
                ->distinct('trip_stops.id')
                ->count('trip_stops.id') ?? 0
        ];
        $tripSpeeds = [
            'name' => 'Trip Speeds',
            'value' => $project?->trips()->leftjoin('trip_speeds', 'trips.id', '=', 'trip_speeds.trip_id')
                ->distinct('trip_speeds.id')
                ->count('trip_speeds.id') ?? 0
        ];
        $recentTrips = $project?->trips()->latest()->limit(8)->get() ?? [];
        $dailyTripCount = $project?->trips()
            ->where('start_time', '>=', now()->startOfDay())
            ->count() ?? 0;

        $totals = [$trips, $users, $tripStops, $tripSpeeds];


        $enumerators = $project?->users ?? collect();

        $labels = [];
        $data = [];
        $backgroundColors = [];

        $colorPalette = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
            '#C9CBCF',
            '#8DD17E',
            '#FFB3E6',
            '#B3E6FF',
            '#E6FFB3',
            '#B3FFE6'
        ];

        foreach ($enumerators as $index => $user) {
            $labels[] = $user->name;
            $userTripCount = $user->trips()->where('project_id', $project->id)->count();
            $data[] = $userTripCount;
            $backgroundColors[] = $colorPalette[$index % count($colorPalette)];
        }

        $tripDistributionChart = [
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Trip Distribution',
                        'data' => $data,
                        'backgroundColor' => $backgroundColors,
                        'borderWidth' => 1,
                    ]
                ]
            ],
            'options' => [
                'responsive' => true
            ]
        ];
        //add legend
        $tripDistributionChart['options']['plugins'] = [
            'legend' => [
                'display' => true,
                'position' => 'top',
                'labels' => [
                    'color' => '#333',
                    'font' => [
                        'size' => 14,
                    ],
                ]
            ]
        ];
        return Inertia::render('Dashboard2', [
            'totals' => $totals,
            'trips' => $recentTrips,
            'dailyTripCount' => $dailyTripCount,
            'tripDistributionData' => $tripDistributionChart,
        ]);
    }
}
