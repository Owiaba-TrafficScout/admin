<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpeedResource extends JsonResource
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
            'time' => $this->time,
            'location_x' => $this->location_x,
            'location_y' => $this->location_y,
            'velocity' => $this->velocity,
            'is_traffic' => $this->is_traffic,
        ];
    }
}
