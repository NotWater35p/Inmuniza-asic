<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perdida extends Model
{
    protected $table = 'perdida';

    protected $fillable = [
        'vacuna_id',
        'lote',
        'cantidad',
        'motivo',
        'observacion',
        'fecha',
        'modulo_id',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'cantidad' => 'integer',
    ];

    const MOTIVOS = [
        'Vencimiento',
        'Rotura',
        'Cadena de frío',
        'Otro',
    ];

    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function scopeDelAsic($query)
    {
        return $query->whereNull('modulo_id');
    }

    public function scopeDelModulo($query, int $moduloId)
    {
        return $query->where('modulo_id', $moduloId);
    }
}