<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AsicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $asicId = $this->route('asic') ? $this->route('asic')->id : null;

        return [
            'rif' => [
                'required',
                'string',
                'max:20',
                Rule::unique('asic', 'rif')->ignore($asicId),
            ],
            'nombre'    => 'required|string|max:150',
            'direccion' => 'required|string|max:500',
            'telefono'  => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'rif.required'       => 'El RIF es obligatorio.',
            'rif.unique'         => 'Ya existe un ASIC con ese RIF.',
            'nombre.required'    => 'El nombre del ASIC es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required'  => 'El teléfono es obligatorio.',
        ];
    }
}