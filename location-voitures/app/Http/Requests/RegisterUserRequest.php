<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'cin' => ['required', 'string', 'max:30', 'unique:users,cin'],
            'numero_permis' => ['required', 'string', 'max:60', 'unique:users,numero_permis'],
            'cin_document' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
            'permis_document' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}

