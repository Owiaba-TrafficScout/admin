<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::all();
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
}
