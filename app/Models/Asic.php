<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asic extends Model
{
    protected $table = 'asic';
    
    protected $fillable = [
        'rif',
        'nombre',
        'direccion',
        'telefono',
    ];

    // Relaciones
    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class);
    }

    public function personal(): HasMany
    {
        return $this->hasMany(Personal::class);
    }

    public function cargas(): HasMany
    {
        return $this->hasMany(Carga::class);
    }

    public function despachos(): HasMany
    {
        return $this->hasMany(Despacho::class);
    }

    public function jornadas(): HasMany
    {
        return $this->hasMany(Jornada::class);
    }

    // Método para calcular inventario (stock actual de cada vacuna)
    public function inventario()
    {
        // Subconsulta para sumar cargas
        $cargas = Carga::select('vacuna_id')
            ->selectRaw('SUM(cantidad) as total_cargas')
            ->where('asic_id', $this->id)
            ->groupBy('vacuna_id');

        // Subconsulta para sumar despachos
        $despachos = Despacho::select('vacuna_id')
            ->selectRaw('SUM(cantidad) as total_despachos')
            ->where('asic_id', $this->id)
            ->groupBy('vacuna_id');

        // Subconsulta para contar tratamientos 
        $tratamientos = Tratamiento::select('vacuna_id')
            ->selectRaw('COUNT(*) as total_aplicaciones')
            ->groupBy('vacuna_id');

        // Consulta principal
        return Vacuna::leftJoinSub($cargas, 'cargas', function ($join) {
        $join->on('vacuna.id', '=', 'cargas.vacuna_id');
    })
    ->leftJoinSub($despachos, 'despachos', function ($join) {
        $join->on('vacuna.id', '=', 'despachos.vacuna_id');
    })
    ->leftJoinSub($tratamientos, 'tratamientos', function ($join) {
        $join->on('vacuna.id', '=', 'tratamientos.vacuna_id');
    })
    ->select('vacuna.*')    // ← CORRECTO
    ->selectRaw('
        COALESCE(cargas.total_cargas, 0) 
        - COALESCE(despachos.total_despachos, 0) 
        - COALESCE(tratamientos.total_aplicaciones, 0) as stock
    ')
    ->get();
    }
}