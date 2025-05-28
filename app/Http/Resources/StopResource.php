<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_time' => $this->start_time,
            'start_location_x' => $this->start_location_x,
            'start_location_y' => $this->start_location_y,
            'stop_time' => $this->stop_time,
            'stop_location_x' => $this->stop_location_x,
            'stop_location_y' => $this->stop_location_y,
            'passenger_count' => $this->passenger_count,
            'passenger_boarding' => $this->passenger_boarding,
            'passenger_alighting' => $this->passenger_alighting,
            'is_traffic' => $this->is_traffic,
        ];
    }
}
