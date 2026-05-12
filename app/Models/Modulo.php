<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulo';

    protected $fillable = [
        'asic_id',
        'rif',
        'nombre',
        'municipio',
        'parroquia',
        'tipo_establecimiento',
        'sispai_fila',
        'direccion',
        'telefono',
        'jefe_cedula',
    ];

    protected $casts = [
        'sispai_fila' => 'integer',
    ];

    const TIPOS_ESTABLECIMIENTO = [
        'CP1', 'CP2', 'CP3', 'HOSPITAL', 'CDI',
        'IVSS', 'IPASME', 'SANIDAD MILITAR', 'PRIVADO', 'OTROS',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function asic(): BelongsTo
    {
        return $this->belongsTo(Asic::class);
    }

    public function jefe(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'jefe_cedula', 'cedula');
    }

    public function despachos(): HasMany
    {
        return $this->hasMany(Despacho::class);
    }

    public function jornadas(): HasMany
    {
        return $this->hasMany(Jornada::class);
    }

    public function perdidas(): HasMany
    {
        return $this->hasMany(Perdida::class);
    }

    // ─── Stock ────────────────────────────────────────────────

    /**
     * Stock disponible de una vacuna en este módulo.
     * = Despachado al módulo - Tratamientos aplicados - Pérdidas del módulo
     */
    public function stockVacuna(int $vacunaId): int
    {
        $despachado = $this->despachos()
            ->where('vacuna_id', $vacunaId)
            ->sum('cantidad');

        $aplicado = Tratamiento::whereHas('jornada', fn($q) => $q->where('modulo_id', $this->id))
            ->where('vacuna_id', $vacunaId)
            ->sum('dosis_aplicada');

        $perdido = $this->perdidas()
            ->where('vacuna_id', $vacunaId)
            ->sum('cantidad');

        return max(0, $despachado - $aplicado - $perdido);
    }
}