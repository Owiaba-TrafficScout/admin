<?php

namespace App\Exports;

use App\Models\TripStop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripStopsSheetExport implements FromCollection, WithHeadings, WithMapping
{
    private $tripId;

    public function __construct($tripId)
    {
        $this->tripId = $tripId;
    }

    public function collection()
    {
        return TripStop::where('trip_id', $this->tripId)->get();
    }

    public function map($tripStop): array
    {
        return [
            $tripStop->id,
            $tripStop->trip_id,
            $tripStop->location_x,
            $tripStop->location_y,
            $tripStop->stop_time,
            $tripStop->description,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Trip ID',
            'Location X',
            'Location Y',
            'Stop Time',
            'Description',
        ];
    }
}
