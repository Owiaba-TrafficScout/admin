<?php

namespace App\Http\Controllers;

use App\Models\CarType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarTypeController extends Controller
{
    public function index()
    {
        $car_types = CarType::all();
        return Inertia::render('CarTypes', ['car_types' => $car_types]);
    }

    public function update(Request $request, CarType $car_type)
    {
        $car_type->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]));

        return redirect()->back();
    }

    public function destroy(CarType $car_type)
    {
        $car_type->delete();
        return redirect()->back();
    }
}
