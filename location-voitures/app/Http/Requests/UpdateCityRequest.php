<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateCityRequest extends StoreCityRequest
{
    public function rules(): array
    {
        $city = $this->route('city');

        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('cities', 'name')->ignore($city->id)],
            'travel_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

