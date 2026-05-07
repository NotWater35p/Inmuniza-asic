<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etnia extends Model
{
    protected $table = 'etnia';
    
    protected $fillable = ['nombre'];

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }
}