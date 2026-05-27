<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Tratamiento extends Model
{
    protected $table = 'tratamiento';

    protected $fillable = [
        'jornada_id',
        'paciente_id',
        'vacuna_id',
        'dosis_aplicada',
        'es_descargo_rapido',
        'subtipo_paciente',
        'fecha_aplicacion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicacion'   => 'date',
        'dosis_aplicada'     => 'integer',
        'es_descargo_rapido' => 'boolean',
    ];

    public function jornada(): BelongsTo
    {
        return $this->belongsTo(Jornada::class);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class);
    }

    /**
     * Calcula la fecha de la próxima dosis basándose
     * en el intervalo/refuerzo de la vacuna y la dosis actual.
     * Solo aplica si hay paciente vinculado.
     */
    public function fechaProximaDosis(): ?Carbon
    {
        if (!$this->paciente_id) return null;

        $vacuna = $this->vacuna;
        if (!$vacuna) return null;

        $base = Carbon::parse($this->fecha_aplicacion);

        // Si ya completó todas las dosis → usa campo refuerzo
        if ($vacuna->numero_dosis && $this->dosis_aplicada >= $vacuna->numero_dosis) {
            return $this->parsearIntervalo($base, $vacuna->refuerzo);
        }

        // Aún tiene dosis pendientes → usa intervalo entre dosis
        return $this->parsearIntervalo($base, $vacuna->intervalo);
    }

    /**
     * Parsea strings como "30 días", "1 mes", "6 meses", "1 año", "21 días"
     */
    protected function parsearIntervalo(Carbon $base, ?string $intervalo): ?Carbon
    {
        if (!$intervalo) return null;

        $texto = strtolower(trim($intervalo));

        if (preg_match('/(\d+)\s*(día|dias|días|day|days)/u', $texto, $m)) {
            return $base->copy()->addDays((int) $m[1]);
        }
        if (preg_match('/(\d+)\s*(semana|semanas|week|weeks)/u', $texto, $m)) {
            return $base->copy()->addWeeks((int) $m[1]);
        }
        if (preg_match('/(\d+)\s*(mes|meses|month|months)/u', $texto, $m)) {
            return $base->copy()->addMonths((int) $m[1]);
        }
        if (preg_match('/(\d+)\s*(año|años|year|years)/u', $texto, $m)) {
            return $base->copy()->addYears((int) $m[1]);
        }

        return null;
    }
}