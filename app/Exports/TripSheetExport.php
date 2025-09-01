<?php

namespace App\Exports;

use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TripSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private $tripId;

    public function __construct($tripId)
    {
        $this->tripId = $tripId;
    }

    public function collection()
    {
        return Trip::where('id', $this->tripId)->get();
    }

    public function map($trip): array
    {
        return [
            $trip->id,
            $trip->title,
            $trip->initial_passenger_count,
            $trip->description,
            $trip->projectUser->user->name,
            $trip->group_code,
            $trip->car->type->name,
            $trip->project->name,
            $trip->start_time,
            $trip->end_time,
        ];
    }
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Initial Passenger Count',
            'Description',
            'User',
            'Group Code',
            'Car Type',
            'Project',
            'Start Time',
            'End Time',
        ];
    }

    public function title(): string
    {
        return 'Trip';
    }
}
