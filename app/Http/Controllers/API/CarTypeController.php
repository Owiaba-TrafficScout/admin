<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarTypeRequest;
use App\Http\Requests\UpdateCarTypeRequest;
use App\Http\Resources\CarTypeResource;
use App\Http\Resources\TemplateResource;
use App\Models\CarType;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    public function index()
    {
        // Logic to retrieve and return a list of cars
        $carTypes = CarType::all();

        return CarTypeResource::collection($carTypes);
    }

    public function store(CarTypeRequest $request)
    {
        // Validate the request data
        $attributes = $request->validated();
        // Create a new car type
        $carType = CarType::create($attributes);

        return new CarTypeResource($carType);
    }

    public function show(CarType $carType)
    {
        // Logic to retrieve and return a specific car type
        return new CarTypeResource($carType);
    }
    public function update(UpdateCarTypeRequest $request, CarType $carType)
    {
        // Validate the request data
        $attributes = $request->validated();
        // Update the car type
        $carType->update($attributes);

        return new CarTypeResource($carType);
    }

    public function destroy(CarType $carType)
    {
        // Logic to delete a specific car type
        $carType->delete();

        return new TemplateResource(
            [
                'status' => 'success',
                'message' => 'Car type deleted successfully',
                'code' => 200,
            ]
        );
    }
}
