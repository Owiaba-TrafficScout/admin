<?php

namespace App\Http\Controllers;

use App\Exports\TripMultiSheetExport;
use App\Exports\TripsExport;
use App\Http\Requests\DeleteMultipleTripsRequest;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class TripController extends Controller
{
    public function index()
    {
        $trips = Project::find(session('project_id'))?->trips()->latest()->get() ?? [];
        return Inertia::render('Trips', ['trips' => $trips]);
    }

    public function  update(Request $request, Trip $trip)
    {
        //validate request
        $attributes = $request->validate([
            'title' => 'required|string|max:255',
            'group_code' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
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

    public function destroyBulk(DeleteMultipleTripsRequest $request)
    {
        $project = Project::find(session('project_id'));
        $attributes = $request->validated();

        // ensure we only delete trips belonging to current project
        $project->trips()
            ->whereIn('trips.id', $attributes['trip_ids'])
            ->delete();

        return redirect()->back()->with('success', 'Selected trips have been deleted.');
    }
}
