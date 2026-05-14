<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
        'created_at'
    ];

    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'usuario_id', 'id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'cliente_id', 'id');
    }
}