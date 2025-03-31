<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoSolucion extends Model
{
    protected $table = "mantenimientos_soluciones";
    protected $fillable = ['mantenimiento_id', 'solucion_id','descripcion'];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'mantenimiento_id');
    }

    public function tipo(){
        return $this->belongsTo(TipoFallo::class, 'solucion_id');
    }
}
