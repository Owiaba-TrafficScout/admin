<?php

namespace App\Exports;

use App\Models\Tenant;
use App\Models\Trip;
use App\Models\TripStop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StopsSheetExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $stops = [];
        $tenant = Tenant::find(session('tenant_id'));

        if (auth()->user()->isAdminInTenant()) {
            $projectIds = $tenant->projects->pluck('id');
            $tripIds = Trip::whereIn('project_id', $projectIds)->pluck('id');
            $stops = TripStop::whereIn('trip_id', $tripIds)->get();
        } else {
            $projectIds = auth()->user()->adminProjects->pluck('id');
            $tripIds = Trip::whereIn('project_id', $projectIds)->pluck('id');
            $stops = TripStop::whereIn('trip_id', $tripIds)->get();
        }
        return $stops;
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
