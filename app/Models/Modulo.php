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
        'direccion',
        'telefono',
        'jefe_cedula',
    ];

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
}