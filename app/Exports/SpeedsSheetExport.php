<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Tenant;
use App\Models\Trip;
use App\Models\TripSpeed;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SpeedsSheetExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $project = Project::find(session('project_id'));
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
            'Velocity',
            'Is Traffic',
        ];
    }
}
