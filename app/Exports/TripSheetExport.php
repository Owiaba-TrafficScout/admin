<?php

namespace App\Exports;

use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripSheetExport implements FromCollection, WithHeadings, WithMapping
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
            $trip->description,
            $trip->user->name,
            $trip->group_code,
            $trip->car->type->name,
            $trip->project->name,
            $trip->start_time,
            $trip->end_time,
            $trip->status->name,
        ];
    }
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'User',
            'Group Code',
            'Car Type',
            'Project',
            'Start Time',
            'End Time',
            'Status',
        ];
    }
}
