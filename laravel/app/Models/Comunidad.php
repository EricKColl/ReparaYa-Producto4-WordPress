<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table = 'comunidades';

    protected $fillable = [
        'gestora_id',
        'nombre',
        'direccion',
        'telefono_contacto',
        'zona',
    ];

    public function gestora()
    {
        return $this->belongsTo(Gestora::class, 'gestora_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'comunidad_id');
    }
}