<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Representante extends Model
{
    protected $table = 'representante';

    protected $primaryKey = 'cedula';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'cedula',
        'telefono',
        'relacion',
    ];

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'representante_id', 'cedula');
    }
}