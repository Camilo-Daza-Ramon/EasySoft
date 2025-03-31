<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoAtencionCliente extends Model
{
    protected $table = "puntos_atencion_clientes";
    protected $fillable = ['turno','motivo_categoria','punto_atencion_id','atencion_cliente_id'];

    public function atencion_cliente(){
        return $this->belongsTo(AtencionCliente::class, 'atencion_cliente_id');
    }

    public function punto_atencion(){
        return $this->belongsTo(PuntoAtencion::class);
    }
}
