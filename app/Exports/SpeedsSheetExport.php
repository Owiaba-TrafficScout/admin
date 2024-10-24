<?php

namespace App\Exports;

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
        $speeds = [];
        $tenant = Tenant::find(session('tenant_id'));
        if (auth()->user()->isAdminInTenant()) {
            $projectIds = $tenant->projects->pluck('id');
            $tripIds = Trip::whereIn('project_id', $projectIds)->pluck('id');
            $speeds = TripSpeed::whereIn('trip_id', $tripIds)->get();
        } else {
            $projectIds = auth()->user()->adminProjects->pluck('id');
            $tripIds = Trip::whereIn('project_id', $projectIds)->pluck('id');
            $speeds = TripSpeed::whereIn('trip_id', $tripIds)->get();
        }
        return $speeds;
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
            'Velocity',
            'Is Traffic',
        ];
    }
}
