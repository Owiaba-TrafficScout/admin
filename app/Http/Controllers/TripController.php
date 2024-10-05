<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::all();
        return Inertia::render('Trips', ['trips' => $trips]);
    }

    public function update(Request $request, Trip $trip)
    {
        //validate request
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'trip_status_id' => 'required',
            'project_id' => 'required',
        ]);
        $trip->update($attributes);
        return redirect()->back();
    }
}
