<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacuna extends Model
{
    protected $table = 'vacuna';

    protected $fillable = [
        'nombre',
        'marca_id',
        'tipo',  
        'presentacion',
        'enfermedad',
        'dosificacion',
        'via_administracion',
        'intervalo',
        'refuerzo',
        'numero_dosis',
        'descripcion',
    ];

    protected $casts = [
        'numero_dosis' => 'integer',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function cargas(): HasMany
    {
        return $this->hasMany(Carga::class);
    }

    public function despachos(): HasMany
    {
        return $this->hasMany(Despacho::class);
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }

    public function perdidas(): HasMany
    {
        return $this->hasMany(Perdida::class);
    }
}
