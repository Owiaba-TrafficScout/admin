<?php

namespace App\Actions;

use App\Models\Trip;

class StoreTripAction
{
    /**
     * Execute the action to store trip data.
     *
     * @param array $data
     * @return Trip
     */
    public function execute(array $data): Trip
    {

        // Create a new stop record in the database
        $trip = Trip::create($data);
        return $trip;
    }
}
