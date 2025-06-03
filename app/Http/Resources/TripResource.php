<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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
            'title' => $this->title,
            'group_code' => $this->group_code,
            'car' => new CarResource($this->whenLoaded('car')),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,

            'stops' => StopResource::collection($this->whenLoaded('stops')),
            'speeds' => SpeedResource::collection($this->whenLoaded('speeds')),
        ];
    }
}
