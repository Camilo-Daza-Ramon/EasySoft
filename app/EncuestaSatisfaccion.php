<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaSatisfaccion extends Model
{
    protected $table = 'encuestas_satisfaccion';
    protected $fillable = ['descripcion','respuesta','estado','archivo'];

    public function respuesta_telefonica(){
        return $this->hasMany(RespuestaTelefonicaV::class, 'pregunta');
    }

    public function respuesta_encuesta_cliente(){
        return $this->hasMany(RespuestaEncuestaCliente::class, 'encuesta_satisfaccion_id');
    }


    public function scopeBuscar($query, $palabra){
        if (!empty($palabra)) {
            $query->where('descripcion','like', '%'.$palabra.'%');
        }
    }
}
