<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Http\Resources\TemplateResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        // Logic to retrieve and return a list of cars
        $cars =  Car::all();

        return CarResource::collection($cars);
    }

    public function store(CarRequest $request)
    {
        // Validate the request data
        $attributes = $request->validated();

        // Create a new car
        $car = Car::create($attributes);

        return new CarResource($car);
    }

    public function show(Car $car)
    {
        // Logic to retrieve and return a specific car
        return new CarResource($car);
    }
    public function update(UpdateCarRequest $request, Car $car)
    {
        // Validate the request data
        $attributes = $request->validated();

        // Update the car
        $car->update($attributes);

        return new CarResource($car);
    }
    public function destroy(Car $car)
    {
        // Logic to delete a specific car
        $car->delete();

        return new TemplateResource([
            'status' => 'success',
            'message' => 'Car deleted successfully',
            'code' => 200,
        ]);
    }
}
