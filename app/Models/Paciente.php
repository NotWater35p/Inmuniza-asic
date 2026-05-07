<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'paciente';

    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
        'etnia_id',
        'representante_id',
        'sector_id',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    public function etnia(): BelongsTo
    {
        return $this->belongsTo(Etnia::class);
    }

    public function representante(): BelongsTo
    {
        return $this->belongsTo(Representante::class, 'representante_id', 'cedula');
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }

    // Scope para pacientes activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
