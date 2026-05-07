<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    protected $table = 'cargo';
    
    protected $fillable = [
        'nombre',
        'nivel_acceso',
    ];

    protected $casts = [
        'nivel_acceso' => 'integer',
    ];

    public function personal(): HasMany
    {
        return $this->hasMany(Personal::class);
    }
}