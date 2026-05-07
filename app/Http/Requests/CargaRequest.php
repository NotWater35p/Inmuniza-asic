<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asic_id'           => 'required|exists:asic,id',
            'vacuna_id'         => 'required|exists:vacuna,id',
            'lote'              => 'required|string|max:100',
            'fecha_llegada'     => 'required|date',
            'fecha_vencimiento' => 'required|date|after:fecha_llegada',
            'cantidad'          => 'required|integer|min:1',
            'observaciones'     => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'asic_id.required'           => 'El ASIC es obligatorio.',
            'asic_id.exists'             => 'El ASIC seleccionado no es válido.',
            'vacuna_id.required'         => 'Debe seleccionar una vacuna.',
            'vacuna_id.exists'           => 'La vacuna seleccionada no es válida.',
            'lote.required'              => 'El número de lote es obligatorio.',
            'lote.max'                   => 'El lote no puede tener más de 100 caracteres.',
            'fecha_llegada.required'     => 'La fecha de llegada es obligatoria.',
            'fecha_llegada.date'         => 'La fecha de llegada no es válida.',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria.',
            'fecha_vencimiento.date'     => 'La fecha de vencimiento no es válida.',
            'fecha_vencimiento.after'    => 'La fecha de vencimiento debe ser posterior a la fecha de llegada.',
            'cantidad.required'          => 'La cantidad es obligatoria.',
            'cantidad.integer'           => 'La cantidad debe ser un número entero.',
            'cantidad.min'               => 'La cantidad debe ser al menos 1.',
        ];
    }
}