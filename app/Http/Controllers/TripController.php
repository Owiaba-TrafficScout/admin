<?php

namespace App\Http\Controllers;

use App\Exports\TripMultiSheetExport;
use App\Exports\TripsExport;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use App\Models\TripStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class TripController extends Controller
{
    public function index()
    {
        $trips = Project::find(session('project_id'))?->trips ?? [];
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
        return redirect()->back()->with('success', 'Trip updated.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->back()->with('success', 'Trip deleted.');
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
