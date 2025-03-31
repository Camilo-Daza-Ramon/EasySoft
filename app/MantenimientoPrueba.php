<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoPrueba extends Model
{
    protected $table = "mantenimientos_pruebas";
    protected $fillable = [
        'mantenimiento_id', 
        'mantenimiento_preventivo_id' ,
        'prueba_id',
        'descripcion'
    ];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'mantenimiento_id');
    }

    public function mantenimiento_preventivo(){
        return $this->belongsTo(MantenimientoPreventivo::class, 'mantenimiento_preventivo_id');
    }

    public function tipo(){
        return $this->belongsTo(TipoFallo::class, 'prueba_id');
    }
}
