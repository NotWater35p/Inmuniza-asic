<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JornadaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
    return [
        'asic_id'              => 'nullable|exists:asic,id',
        'modulo_id'            => 'nullable|exists:modulo,id',
        'fecha_jornada'        => 'required|date',
        'descripcion'          => 'nullable|string',
        'personal_responsable' => 'required|exists:personal,cedula',
    ];
}
}
