<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => ['required', 'exists:cars,id'],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'date_debut' => ['required', 'date', 'after_or_equal:today'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
        ];
    }

    public function attributes(): array
    {
        return [
            'city_id' => 'ville',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
        ];
    }
}
