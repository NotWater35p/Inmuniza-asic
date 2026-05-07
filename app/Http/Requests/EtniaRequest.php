<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtniaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:60|unique:etnia,nombre',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la etnia es obligatorio.',
            'nombre.unique'   => 'Ya existe una etnia con este nombre.',
        ];
    }
}