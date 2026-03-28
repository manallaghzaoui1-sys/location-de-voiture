<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIdentityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'cin' => ['required', 'string', 'max:30', Rule::unique('users', 'cin')->ignore($userId)],
            'numero_permis' => ['required', 'string', 'max:60', Rule::unique('users', 'numero_permis')->ignore($userId)],
            'cin_document' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
            'permis_document' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
        ];
    }
}

