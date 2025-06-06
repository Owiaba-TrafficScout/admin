<?php

namespace App\Exports;

use App\Models\TripSpeed;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripSpeedsSheetExport implements FromCollection, WithHeadings, WithMapping
{
    private $tripId;

    public function __construct($tripId)
    {
        $this->tripId = $tripId;
    }

    public function collection()
    {
        return TripSpeed::where('trip_id', $this->tripId)->get();
    }

    public function map($tripSpeed): array
    {
        return [
            $tripSpeed->id,
            $tripSpeed->trip_id,
            $tripSpeed->location_x,
            $tripSpeed->location_y,
            $tripSpeed->velocity,
            $tripSpeed->is_traffic,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Trip ID',
            'Location X',
            'Location Y',
            'Speed(km/h)',
            'Is Traffic',
        ];
    }
}
