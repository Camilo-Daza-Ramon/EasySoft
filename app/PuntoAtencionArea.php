<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoAtencionArea extends Model
{
    protected $table = "puntos_atencion_areas";
    protected $fillable = ['nombre','punto_atencion_id'];

    public function punto_atencion(){
        return $this->belongsTo(PuntoAtencion::class);
    }

    public function punto_atencion_ventanilla(){
        return $this->hasMany(PuntoAtencionVentanilla::class, 'punto_atencion_area_id');
    }
}
