<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Modulo;

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
            'asic_id'              => 'required|exists:asic,id',
            'rif'                  => [
                'required', 'string', 'max:20',
                'regex:/^[JGjg]\-\d{8}\-\d$/',
                Rule::unique('modulo', 'rif')->ignore($moduloId),
            ],
            'nombre'               => 'required|string|max:150',
            'municipio'            => 'nullable|string|max:100|regex:/^[\pL\s]+$/u',
            'parroquia'            => 'nullable|string|max:100|regex:/^[\pL\s]+$/u',
            'tipo_establecimiento' => ['required', Rule::in(Modulo::TIPOS_ESTABLECIMIENTO)],
            'sispai_fila'          => 'nullable|integer|min:1|max:999',
            'direccion'            => 'nullable|string|max:255',
            'telefono'             => 'nullable|string|max:20|regex:/^[\d\s\+\-\(\)]+$/',
            'jefe_cedula'          => [
                'nullable', 'integer',
                Rule::exists('personal', 'cedula')->where(fn($q) =>
                    $q->whereIn('cargo_id', fn($sub) =>
                        $sub->select('id')->from('cargo')->where('nivel_acceso', 2)
                    )
                ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'asic_id.required'             => 'Debe seleccionar un ASIC.',
            'rif.required'                 => 'El RIF es obligatorio.',
            'rif.unique'                   => 'Ya existe un módulo con este RIF.',
            'rif.regex'                    => 'El RIF debe tener el formato J-12345678-9.',
            'nombre.required'              => 'El nombre del módulo es obligatorio.',
            'municipio.regex'              => 'El municipio solo puede contener letras y espacios.',
            'parroquia.regex'              => 'La parroquia solo puede contener letras y espacios.',
            'tipo_establecimiento.required'=> 'Debe seleccionar el tipo de establecimiento.',
            'tipo_establecimiento.in'      => 'El tipo seleccionado no es válido.',
            'sispai_fila.integer'          => 'La fila SISPAI debe ser un número entero.',
            'sispai_fila.min'              => 'La fila SISPAI debe ser mayor a 0.',
            'sispai_fila.max'              => 'La fila SISPAI no puede superar 999.',
            'telefono.regex'               => 'El teléfono solo puede contener números, espacios y + - ( ).',
            'jefe_cedula.exists'           => 'El personal seleccionado no existe o no tiene cargo de Jefe de Módulo.',
        ];
    }
}