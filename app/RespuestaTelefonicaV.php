<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestaTelefonicaV extends Model
{
    protected $table = 'respuestas_telefonicasV';
    protected $fillable = ['cedula',' pregunta', 'respuesta', 'fecha_hora', 'llamada_id', 'telefono'];

    public function encuesta(){
        return $this->belongsTo(EncuestaSatisfaccion::class, 'pregunta');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cedula', 'Identificacion');
    }

}
