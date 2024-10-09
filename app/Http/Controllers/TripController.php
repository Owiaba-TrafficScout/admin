<?php

namespace App\Http\Controllers;

use App\Exports\TripMultiSheetExport;
use App\Exports\TripsExport;
use App\Models\Trip;
use App\Models\TripStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class TripController extends Controller
{
    public function index()
    {
        $trips = null;
        if (auth()->user()->isSystemAdmin()) {
            $trips = Trip::all();
        } else {
            //i want to retrieve all trips with project id same as the user's project id
            //I'll get all user's project ids
            $projectIds = auth()->user()->projects->pluck('id');

            //retrieve all trips with project id same as the user's project id
            $trips = Trip::whereIn('project_id', $projectIds)->get();
        }

        $statuses = TripStatus::all();
        return Inertia::render('Trips', ['trips' => $trips, 'statuses' => $statuses]);
    }

    public function  update(Request $request, Trip $trip)
    {
        //validate request
        $attributes = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'group_code' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'trip_status_id' => 'required',
        ]);
        $trip->update($attributes);
        return redirect()->back();
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->back();
    }

    public function exportTripsToExcel()
    {
        return Excel::download(new TripsExport, 'trips.xlsx');
    }

    public function exportTripToExcel($tripId)
    {
        return Excel::download(new TripMultiSheetExport($tripId), 'trip-details.xlsx');
    }
}
