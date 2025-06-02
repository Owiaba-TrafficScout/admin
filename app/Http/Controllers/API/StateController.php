<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StateRequest;
use App\Http\Resources\StateResource;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function update(StateRequest $request)
    {
        $attributes = $request->validated();

        // Update the state for the authenticated user
        $state = $request->user()->state()->updateOrCreate(['user_id' => $request->user()->id], $attributes);

        return new StateResource($state);
    }
}
