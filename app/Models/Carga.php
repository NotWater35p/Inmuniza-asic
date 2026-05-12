<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carga extends Model
{
    protected $table = 'carga';

    protected $fillable = [
        'asic_id',
        'vacuna_id',
        'lote',
        'fecha_llegada',
        'fecha_vencimiento',
        'cantidad',
        'cantidad_disponible',
        'observaciones',
    ];

    protected $casts = [
        'fecha_llegada'       => 'date',
        'fecha_vencimiento'   => 'date',
        'cantidad'            => 'integer',
        'cantidad_disponible' => 'integer',
    ];

    public function asic(): BelongsTo
    {
        return $this->belongsTo(Asic::class);
    }

    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class);
    }
}