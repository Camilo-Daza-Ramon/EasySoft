<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestaEncuestaCliente extends Model
{
    protected $table = "respuestas_encuestas_clientes";
    protected $fillable = ['pregunta','respuesta','atencion_cliente_id','encuesta_satisfaccion_id'];

    public function encuesta(){
        return $this->belongsTo(EncuestaSatisfaccion::class, 'encuesta_satisfaccion_id');
    }

    public function atencion_cliente(){
        return $this->belongsTo(AtencionCliente::class, 'atencion_cliente_id');
    }
}
