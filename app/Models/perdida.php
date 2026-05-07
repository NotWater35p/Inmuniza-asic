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
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class);
    }
}