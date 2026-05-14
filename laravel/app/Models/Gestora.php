<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestora extends Model
{
    protected $table = 'gestoras';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'comision',
    ];

    // No mostrar la contraseña al convertir a array/JSON
    protected $hidden = ['password'];

    // Una gestora tiene muchas comunidades
    public function comunidades()
    {
        return $this->hasMany(Comunidad::class, 'gestora_id');
    }

    // Una gestora tiene muchas incidencias (servicios tramitados por ella)
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'gestora_id');
    }

    /**
     * Calcula el total de comisiones de la gestora en un mes concreto.
     * $mes y $anyo son opcionales; si no se pasan, usa el mes actual.
     */
    public function totalComisionesMes(int $mes = null, int $anyo = null): float
    {
        $mes  = $mes  ?? now()->month;
        $anyo = $anyo ?? now()->year;

        return $this->incidencias()
            ->where('estado', 'Finalizada')
            ->whereMonth('fecha_servicio', $mes)
            ->whereYear('fecha_servicio', $anyo)
            ->get()
            ->sum(function ($incidencia) {
                // comisión = precio_base * porcentaje / 100
                return $incidencia->precio_base * ($this->comision / 100);
            });
    }
}
