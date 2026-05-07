<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personal extends Model
{
    protected $table = 'personal';
    
    protected $primaryKey = 'cedula';
    public $incrementing = false;
    protected $keyType = 'int';
    
    protected $fillable = [
        'cedula',
        'asic_id',
        'nombre',
        'apellido',
        'cargo_id',
        'telefono',
        'correo',
    ];

    // Relaciones
    public function asic(): BelongsTo
    {
        return $this->belongsTo(Asic::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'personal_cedula', 'cedula');
    }

    public function despachosEnviados(): HasMany
    {
        return $this->hasMany(Despacho::class, 'responsable_envio', 'cedula');
    }

    public function jornadasResponsable(): HasMany
    {
        return $this->hasMany(Jornada::class, 'personal_responsable', 'cedula');
    }
}