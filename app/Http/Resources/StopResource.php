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
            'passengers_count' => $this->passengers_count,
            'passengers_boarding' => $this->passengers_boarding,
            'passengers_alighting' => $this->passengers_alighting,
            'is_traffic' => $this->is_traffic,
        ];
    }
}
