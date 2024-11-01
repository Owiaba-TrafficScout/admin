<?php

namespace App\Http\Controllers;

use App\Models\CarType;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarTypeController extends Controller
{
    public function index()
    {
        $project = Project::find(session('project_id'))->load('carTypes');
        return Inertia::render('CarTypes', ['car_types' => $project->carTypes]);
    }

    public function update(Request $request, CarType $car_type)
    {
        $car_type->update($request->validate([
            'name' => 'required|string|max:255',
        ]));

        return redirect()->back()->with('success', 'Car Type updated.');
    }

    public function destroy(CarType $car_type)
    {
        $car_type->delete();
        return redirect()->back()->with('success', 'Car Type deleted.');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CarType::create($attributes);
        return redirect()->back()->with('success', 'Car Type created.');
    }

    public function addCarType(Request $request, Project $project)
    {
        $request->validate([
            'car_type_ids' => 'required|array',
            'car_type_ids.*' => 'integer|exists:car_types,id',
        ]);
        $project->carTypes()->syncWithoutDetaching($request->car_type_ids);
        return redirect()->back()->with('success', 'Car Type added to project.');
    }
}
