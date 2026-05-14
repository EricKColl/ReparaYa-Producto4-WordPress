<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'localizador',
        'cliente_id',
        'tecnico_id',
        'especialidad_id',
        'descripcion',
        'direccion',
        'telefono_contacto',
        'fecha_servicio',
        'tipo_urgencia',
        'estado',
        'created_at',
        'gestora_id',
        'comunidad_id',
        'precio_base',
    ];

    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function gestora()
    {
        return $this->belongsTo(Gestora::class, 'gestora_id');
    }

    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }
}
