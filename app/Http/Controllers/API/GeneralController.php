<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Actions\StoreTripAction;
use App\Actions\StoreStopAction;
use App\Actions\StoreSpeedAction;
use App\Http\Requests\UploadDataRequest;
use App\Models\Car;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function uploadData(UploadDataRequest $request, StoreStopAction $storeStopAction, StoreSpeedAction $storeSpeedAction, StoreTripAction $storeTripAction)
    {
        $attributes = $request->validated();

        DB::beginTransaction();

        try {
            // Store trip
            $tripData = $attributes['trip'];

            //find project user and get his id
            $projectId = $tripData['project_id'];
            $projectUserId = ProjectUser::where('user_id', Auth::id())->where('project_id', $projectId)->value('id');
            if (!$projectUserId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized project access'], 403);
            }
            $tripData['project_user_id'] = $projectUserId;
            //drop project_id
            unset($tripData['project_id']);

            //create a new car with the provided car_type_id and retrieve it's id
            $carTypeId = $tripData['car_type_id'];
            $carId = Car::create([
                'car_type_id' => $carTypeId,
                'car_number' => 'Car ' . uniqid(),
            ]);
            $tripData['car_id'] = $carId->id;
            //drop car_type_id
            unset($tripData['car_type_id']);


            $trip = $storeTripAction->execute($tripData);

            // Store stops
            $stops = $attributes['stops'] ?? [];
            foreach ($stops as $stop) {
                $stop['trip_id'] = $trip->id;
                $storeStopAction->execute($stop);
            }

            // Store speeds
            $speeds = $attributes['speeds'] ?? [];
            foreach ($speeds as $speed) {
                $speed['trip_id'] = $trip->id;
                $storeSpeedAction->execute($speed);
            }

            DB::commit();

            return response()->json(['success' => true, 'trip_id' => $trip->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
