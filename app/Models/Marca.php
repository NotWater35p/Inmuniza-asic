<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    protected $table = 'marca';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function vacunas(): HasMany
    {
        return $this->hasMany(Vacuna::class);
    }
}