<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratoArchivo extends Model
{
     protected $table = 'contratos_archivos';

    protected $fillable = ['nombre', 'archivo', 'tipo_archivo', 'estado', 'contrato_id'];

    public function contrato(){
    	return $this->belongsTo(ClienteContrato::class, 'contrato_id');
    }
}
