<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre_especialidad'
    ];

    public function tecnicos()
    {
        return $this->hasMany(Tecnico::class, 'especialidad_id', 'id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'especialidad_id', 'id');
    }
}