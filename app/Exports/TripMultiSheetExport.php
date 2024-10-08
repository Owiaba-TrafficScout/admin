<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TripMultiSheetExport implements WithMultipleSheets
{
    private $tripId;

    public function __construct($tripId)
    {
        $this->tripId = $tripId;
    }

    /**
     * Create sheets for Trip, TripStops, and TripSpeeds.
     */
    public function sheets(): array
    {
        return [
            'Trip' => new TripSheetExport($this->tripId),
            'Trip Stops' => new TripStopsSheetExport($this->tripId),
            'Trip Speeds' => new TripSpeedsSheetExport($this->tripId),
        ];
    }
}
