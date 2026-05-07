<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\TipoEstablecimiento;

class ModuloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $moduloId = $this->route('modulo');

    return [
        'asic_id'             => 'required|exists:asic,id',
        'rif'                 => ['required', 'string', 'max:20', Rule::unique('modulo', 'rif')->ignore($moduloId)],
        'nombre'              => 'required|string|max:150',
        'municipio'           => 'nullable|string|max:100',
        'parroquia'           => 'nullable|string|max:100',
        'tipo_establecimiento'=> ['required', Rule::in(TipoEstablecimiento::valores())],
        'direccion'           => 'nullable|string',
        'telefono'            => 'nullable|string|max:20',
        'jefe_cedula'         => [
            'nullable', 'integer', 'exists:personal,cedula',
            Rule::exists('personal', 'cedula')->where(function ($query) {
                $query->whereIn('cargo_id', function ($subquery) {
                    $subquery->select('id')->from('cargo')->where('nivel_acceso', 2);
                });
            }),
        ],
    ];
}

    public function messages(): array
    {
        return [
            'asic_id.required'      => 'Debe seleccionar un ASIC.',
            'rif.required'          => 'El RIF es obligatorio.',
            'rif.unique'            => 'Ya existe un módulo con este RIF.',
            'nombre.required'       => 'El nombre del módulo es obligatorio.',
            'jefe_cedula.exists'    => 'El personal seleccionado no existe o no tiene cargo de Jefe de Módulo.',
        ];
    }
}
