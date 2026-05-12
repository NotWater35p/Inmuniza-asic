<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DespachoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asic_id'           => 'required|exists:asic,id',
            'modulo_id'         => 'required|exists:modulo,id',
            'vacuna_id'         => 'required|exists:vacuna,id',
            'fecha_envio'       => 'required|date|before_or_equal:today',
            'responsable_envio' => 'required|exists:personal,cedula',
            'lote'              => 'required|string|max:50', 
            'cantidad'          => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'asic_id.required'            => 'El ASIC es obligatorio.',
            'modulo_id.required'          => 'Debes seleccionar un módulo destino.',
            'vacuna_id.required'          => 'Debes seleccionar una vacuna.',
            'fecha_envio.required'        => 'La fecha de envío es obligatoria.',
            'fecha_envio.before_or_equal' => 'La fecha de envío no puede ser futura.',
            'responsable_envio.required'  => 'Debes seleccionar un responsable.',
            'lote.required'               => 'Debes seleccionar un lote. Es necesario para el control de inventario.',
            'cantidad.required'           => 'La cantidad es obligatoria.',
            'cantidad.min'                => 'La cantidad mínima es 1 dosis.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('asic_id')) {
            $asic = \App\Models\Asic::first();
            $this->merge(['asic_id' => $asic?->id]);
        }
    }
}