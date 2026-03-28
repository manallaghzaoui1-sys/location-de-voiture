<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'marque' => ['required', 'string', 'max:100'],
            'modele' => ['required', 'string', 'max:100'],
            'annee' => ['required', 'integer', 'min:1990', 'max:' . date('Y')],
            'carburant' => ['required', 'string', 'max:60'],
            'prix_par_jour' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'description' => ['nullable', 'string', 'max:2000'],
            'disponible' => ['nullable', 'boolean'],
        ];
    }
}

