<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratoServicio extends Model
{
    protected $table = 'contratos_servicios';
    protected $fillable = ['nombre', 'descripcion', 'cantidad', 'unidad_medida', 'valor', 'iva', 'estado', 'contrato_id', 'tipo_servicio'];

    public function contrato(){
    	return $this->belongsTo(ClienteContrato::class);
    }
}
