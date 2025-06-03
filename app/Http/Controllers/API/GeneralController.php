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

class GeneralController extends Controller
{
    public function uploadData(UploadDataRequest $request, StoreStopAction $storeStopAction, StoreSpeedAction $storeSpeedAction, StoreTripAction $storeTripAction)
    {
        $attributes = $request->validated();

        DB::beginTransaction();

        try {
            // Store trip
            $tripData = $attributes['trip'];
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
