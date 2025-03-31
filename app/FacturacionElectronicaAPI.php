<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturacionElectronicaAPI extends Model
{
    protected $table = "facturacion_electronica_apis";
    protected $fillable = ['url_api', 'token_identificador', 'controlador', 'accion', 'proyecto_id'];


    public function proyecto(){
    	return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }
}
