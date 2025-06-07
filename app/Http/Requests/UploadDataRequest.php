<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trip' => 'required|array',
            'trip.title' => 'required|string|max:255',
            'trip.project_id' => 'required|exists:projects,id',
            'trip.tenant_id' => 'required|exists:tenants,id',
            'trip.group_code' => 'required|string|max:255',
            'trip.car_type_id' => 'required|exists:car_types,id',
            'trip.start_time' => 'required|date',
            'trip.end_time' => 'required|date',
            'stops' => 'array',
            'stops.*.start_time' => 'nullable|date',
            'stops.*.start_location_x' => 'nullable|numeric',
            'stops.*.start_location_y' => 'nullable|numeric',
            'stops.*.stop_time' => 'nullable|date',
            'stops.*.stop_location_x' => 'nullable|numeric',
            'stops.*.stop_location_y' => 'nullable|numeric',
            'stops.*.passengers_count' => 'nullable|integer',
            'stops.*.passengers_boarding' => 'nullable|integer',
            'stops.*.passengers_alighting' => 'nullable|integer',
            'stops.*.is_traffic' => 'nullable|boolean',
            'speeds' => 'array',
            'speeds.*.time' => 'nullable|date',
            'speeds.*.location_x' => 'nullable|numeric',
            'speeds.*.location_y' => 'nullable|numeric',
            'speeds.*.velocity' => 'nullable|numeric',
            'speeds.*.is_traffic' => 'nullable|boolean'
        ];
    }
}
