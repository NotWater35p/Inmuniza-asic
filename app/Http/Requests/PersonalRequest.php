<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtiene el valor del parámetro de ruta 'personal' (la cédula)
        $cedula = $this->route('personal');

        return [
            'cedula'   => [
                'required',
                'integer',
                Rule::unique('personal', 'cedula')->ignore($cedula, 'cedula'),
            ],
            'asic_id'  => 'required|exists:asic,id',
            'nombre'   => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'cargo_id' => 'required|exists:cargo,id',
            'telefono' => 'nullable|string|max:20',
            'correo'   => 'nullable|email|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'cedula.required'  => 'La cédula es obligatoria.',
            'cedula.unique'    => 'Ya existe un empleado con esta cédula.',
            'asic_id.required' => 'Debe seleccionar un ASIC.',
            'cargo_id.required'=> 'Debe seleccionar un cargo.',
        ];
    }
}