<?php

namespace App\Actions;

use App\Models\TripStop;

class StoreStopAction
{
    /**
     * Execute the action to store stop data.
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data): void
    {

        // Create a new stop record in the database
        TripStop::create($data);
    }
}
