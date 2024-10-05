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
}
