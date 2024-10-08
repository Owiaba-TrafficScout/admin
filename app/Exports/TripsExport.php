<?php

namespace App\Exports;

use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TripsExport  implements WithMultipleSheets
{
    /**
     * Create sheets for Trip, TripStops, and TripSpeeds.
     */
    public function sheets(): array
    {
        return [
            'Trips' => new TripsSheetExport(),
            'Stops' => new StopsSheetExport(),
            'Speeds' => new SpeedsSheetExport(),
        ];
    }
}
