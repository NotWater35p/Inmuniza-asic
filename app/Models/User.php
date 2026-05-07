<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'personal_cedula',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'personal_cedula', 'cedula');
    }

    public function getCargoAttribute()
    {
        return $this->personal?->cargo;
    }

    public function getNivelAccesoAttribute(): int
    {
        return $this->cargo?->nivel_acceso ?? 0;
    }

    // El módulo que dirige este usuario (si es Jefe de Módulo)
    public function modulo()
    {
        return Modulo::where('jefe_cedula', $this->personal_cedula)->first();
    }

    public function esAdmin(): bool
    {
        return $this->nivel_acceso >= 3;
    }

    public function esJefeModulo(): bool
    {
        return $this->nivel_acceso === 2;
    }
}