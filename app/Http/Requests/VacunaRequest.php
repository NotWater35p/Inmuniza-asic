<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'             => 'required|string|max:100',
            'marca_id'           => 'required|exists:marca,id',
            'presentacion'       => 'nullable|string|max:50',
            'enfermedad'         => 'nullable|string|max:100',
            'dosificacion'       => 'nullable|string|max:100',
            'via_administracion' => 'nullable|string|max:50',
            'intervalo'          => 'nullable|string|max:50',
            'refuerzo'           => 'nullable|string|max:50',
            'numero_dosis'       => 'nullable|integer|min:1',
            'descripcion'        => 'nullable|string',
            'tipo'               => 'required|in:vacuna,suero,insumo',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre de la vacuna es obligatorio.',
            'marca_id.required' => 'Debe seleccionar una marca.',
            'marca_id.exists'   => 'La marca seleccionada no es válida.',
        ];
    }
}