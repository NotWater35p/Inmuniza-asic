<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RepresentanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cedula = $this->route('representante')
            ? $this->route('representante')->cedula
            : null;

        return [
            'cedula'   => [
                'required',
                'integer',
                Rule::unique('representante', 'cedula')->ignore($cedula, 'cedula'),
            ],
            'telefono' => 'nullable|string|max:20',
            'relacion' => 'nullable|string|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.integer'  => 'La cédula debe ser un número entero.',
            'cedula.unique'   => 'Ya existe un representante con esta cédula.',
        ];
    }
}