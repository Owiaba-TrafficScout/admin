<?php

namespace App\Actions;

use App\Models\TripSpeed;

class StoreSpeedAction
{
    /**
     * Execute the action to store speed data.
     *
     * @param array $data
     * @return Speed
     */
    public function execute(array $data): void
    {
        $speed = TripSpeed::create($data);
    }
}
