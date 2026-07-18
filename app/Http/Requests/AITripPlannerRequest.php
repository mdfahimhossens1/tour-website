<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AITripPlannerRequest extends FormRequest
{
public function authorize(): bool
{
    return true;
}

public function rules(): array
{
    return [

        'from_location' => 'nullable|string|max:255',

        'destination' => 'required|string|max:255',

        'days' => 'required|integer|min:1|max:30',

        'travelers' => 'required|integer|min:1|max:50',

        'budget' => 'required|numeric|min:1',

        'travel_type' => 'nullable|string|max:100',

        'interests' => 'nullable|array',

        'interests.*' => 'string|max:100',

        'hotel_type' => 'nullable|string|max:100',

        'transport' => 'nullable|string|max:100',

        'extra_note' => 'nullable|string|max:1000',

    ];
}
}
