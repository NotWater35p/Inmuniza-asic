<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TratamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jornada_id'       => 'required|exists:jornada,id',
            'paciente_id'      => 'required|exists:paciente,id',
            'vacuna_id'        => 'required|exists:vacuna,id',
            'dosis_aplicada'   => 'required|integer|min:1',
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'observaciones'    => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'jornada_id.required'      => 'Debe seleccionar una jornada.',
            'jornada_id.exists'        => 'La jornada seleccionada no es válida.',
            'paciente_id.required'     => 'Debe seleccionar un paciente.',
            'paciente_id.exists'       => 'El paciente no está registrado en el sistema.',
            'vacuna_id.required'       => 'Debe seleccionar una vacuna.',
            'dosis_aplicada.required'  => 'El número de dosis es obligatorio.',
            'dosis_aplicada.min'       => 'El número de dosis debe ser al menos 1.',
            'fecha_aplicacion.required'        => 'La fecha de aplicación es obligatoria.',
            'fecha_aplicacion.before_or_equal' => 'La fecha de aplicación no puede ser futura.',
        ];
    }
}