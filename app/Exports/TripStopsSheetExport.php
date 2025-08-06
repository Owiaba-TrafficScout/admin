<?php

namespace App\Exports;

use App\Models\TripStop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TripStopsSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle
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
            $tripStop->start_time,
            $tripStop->start_location_x,
            $tripStop->start_location_y,
            $tripStop->stop_time,
            $tripStop->stop_location_x,
            $tripStop->stop_location_y,
            $tripStop->passengers_count,
            $tripStop->passengers_boarding,
            $tripStop->passengers_alighting,
            $tripStop->is_traffic,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Trip ID',
            'Start Time',
            'Start Location X',
            'Start Location Y',
            'Stop Time',
            'Stop Location X',
            'Stop Location Y',
            'Passengers Count',
            'Passengers Boarding',
            'Passengers Alighting',
            'Is Traffic',
        ];
    }

    public function title(): string
    {
        return 'Stops';
    }
}
