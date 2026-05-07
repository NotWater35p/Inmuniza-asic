<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despacho extends Model
{
    protected $table = 'despacho';
    
    protected $fillable = [
        'asic_id',
        'modulo_id',
        'vacuna_id',
        'fecha_envio',
        'responsable_envio',
        'lote', 
        'cantidad',
    ];

    protected $casts = [
        'fecha_envio' => 'date',
        'cantidad' => 'integer',
    ];

    public function asic(): BelongsTo
    {
        return $this->belongsTo(Asic::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'responsable_envio', 'cedula');
    }
}