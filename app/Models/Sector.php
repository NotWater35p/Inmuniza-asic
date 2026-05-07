<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    protected $table = 'sector';
    
    protected $fillable = ['nombre'];

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }
}