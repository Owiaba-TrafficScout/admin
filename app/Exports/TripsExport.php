<?php

namespace App\Exports;

use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Trip::all();
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
            $trip->stops->pluck('stop_time')->implode(', '),
            // Include related TripSpeeds data (concatenate if there are multiple speeds)
            $trip->speeds->pluck('velocity')->implode(', '),
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
            'Stops',
            'Speeds',
        ];
    }
}
