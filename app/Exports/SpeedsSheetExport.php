<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use App\Models\TripSpeed;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SpeedsSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function collection()
    {
        $state = auth()->user()->state;
        $project = Project::findorFail($state?->project_id);
        $trips = $project->trips;
        $speeds = TripSpeed::whereIn('trip_id', $trips->pluck('id'))->get();
        return $speeds;
    }

    public function map($tripSpeed): array
    {
        return [
            $tripSpeed->id,
            $tripSpeed->trip_id,
            $tripSpeed->time,
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
            'Time',
            'Location X',
            'Location Y',
            'Speed(km/h)',
            'Is Traffic',
        ];
    }

    public function title(): string
    {
        return 'Speeds';
    }
}
