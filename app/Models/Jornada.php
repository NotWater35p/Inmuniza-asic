<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jornada extends Model
{
    protected $table = 'jornada';

    protected $fillable = [
        'asic_id',
        'modulo_id',
        'fecha_jornada',
        'descripcion',
        'personal_responsable',
    ];

    protected $casts = [
        'fecha_jornada' => 'date',
    ];

    public function asic(): BelongsTo
    {
        return $this->belongsTo(Asic::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'personal_responsable', 'cedula');
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }

    public function totalPacientes(): int
    {
        return $this->tratamientos()->distinct('paciente_id')->count();
    }

    public function totalDosis(): int
    {
        return $this->tratamientos()->sum('dosis_aplicada');
    }
}
