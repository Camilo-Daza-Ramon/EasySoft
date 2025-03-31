<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoAtencionVentanilla extends Model
{
    protected $table = "puntos_atencion_ventanillas";
    protected $fillable = ['nombre','punto_atencion_area_id','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function punto_atencion_area(){
        return $this->belongsTo(PuntoAtencionArea::class);
    }
}
