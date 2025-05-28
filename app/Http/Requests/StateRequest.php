<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StateRequest extends FormRequest
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
        $user = User::find(Auth::id());
        return [
            'project_id' => [
                'required',
                'exists:projects,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (!$user->projects()->where('projects.id', $value)->exists()) {
                        $fail('You do not have access to this project.');
                    }
                },
            ],
        ];
    }
}
