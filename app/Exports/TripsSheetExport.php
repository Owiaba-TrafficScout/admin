<?php

namespace App\Exports;

use App\Models\Tenant;
use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TripsSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $trips = [];
        $tenant = Tenant::find(session('tenant_id'));

        if (auth()->user()->isAdminInTenant()) {
            $trips = $tenant->trips;
        } else {
            $trips = auth()->user()->adminTrips();
        }
        return $trips;
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
            'Stops',
            'Speeds',
        ];
    }
    public function title(): string
    {
        return 'Trips';
    }
}
