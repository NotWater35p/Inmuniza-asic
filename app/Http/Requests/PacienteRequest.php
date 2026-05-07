<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente');

        return [
            'cedula'           => [
                'nullable',
                'integer',
                Rule::unique('paciente', 'cedula')->ignore($pacienteId),
            ],
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'sexo'             => 'required|in:M,F',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:255',
            'etnia_id'         => 'nullable|exists:etnia,id',
            'sector_id'        => 'nullable|exists:sector,id',
            'activo'           => 'nullable|boolean',

            // Representante 
            'representante'           => 'nullable|array',
            'representante.cedula'    => 'nullable|integer',
            'representante.telefono'  => 'nullable|string|max:20',
            'representante.relacion'  => 'nullable|string|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required'                 => 'El nombre del paciente es obligatorio.',
            'apellidos.required'               => 'El apellido del paciente es obligatorio.',
            'fecha_nacimiento.required'        => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'sexo.required'                    => 'El sexo es obligatorio.',
            'sexo.in'                          => 'El sexo debe ser Masculino o Femenino.',
            'cedula.unique'                    => 'Ya existe un paciente con esta cédula.',
        ];
    }
}