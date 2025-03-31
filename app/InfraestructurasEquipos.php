<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfraestructurasEquipos extends Model
{
    protected $table = 'infraestructuras_equipos';
    protected $fillable = [
        'inventario_id',
        'ip_gestion',
        'usuario',
        'password',
        'infraestructura_id'
    ];

    public function activo_fijo(){
        return $this->belongsTo(ActivoFijo::class, 'inventario_id', 'ActivoFijoId');
    }
}
