<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this['status'] ?? 'success',
            'message' => $this['message'] ?? 'Transaction completed',
            'code' => $this['code'] ?? 200,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->resource['code'] ?? 200);
    }
}
