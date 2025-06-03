<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'car_number' => $this->car_number,
            'car_type' => $this->whenLoaded('type', function () {
                return new CarTypeResource($this->type);
            }),
        ];
    }
}
