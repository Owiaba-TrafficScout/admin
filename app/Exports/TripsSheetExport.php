<?php

namespace App\Exports;

use App\Models\Tenant;
use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripsSheetExport implements FromCollection, WithHeadings, WithMapping
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
            $trip->description,
            $trip->projectUser->user->name,
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
            'Stops',
            'Speeds',
        ];
    }
}
