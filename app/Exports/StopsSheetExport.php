<?php

namespace App\Exports;

use App\Models\TripStop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StopsSheetExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TripStop::all();
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
