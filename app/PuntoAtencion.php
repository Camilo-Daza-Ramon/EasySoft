<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoAtencion extends Model
{
    protected $table = "puntos_atencion";
    protected $fillable = ['nombre','direccion','barrio','latitud','longitud','municipio_id','proyecto_id','estado'];

    public function punto_atencion_area(){
        return $this->hasMany(PuntoAtencionArea::class);
    }

    public function punto_atencion_cliente(){
        return $this->hasMany(PuntoAtencionCliente::class);
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class,'municipio_id');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
