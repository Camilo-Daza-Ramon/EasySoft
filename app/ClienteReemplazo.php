<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteReemplazo extends Model
{
    protected $table = 'clientes_reemplazos';
    protected $fillable = ['meta_cliente_id', 'antiguo_cliente_contrato_id', 'cliente_nuevo_id', 'nuevo_cliente_contrato_id', 'fecha_reemplazo', 'observacion','cliente_reemplazo_id'];


    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'cliente_nuevo_id');
    }

    public function contrato_antiguo(){
    	return $this->belongsTo(ClienteContrato::class, 'antiguo_cliente_contrato_id');
    }

    public function contrato_nuevo(){
    	return $this->belongsTo(ClienteContrato::class, 'nuevo_cliente_contrato_id');
    }

    public function meta_cliente(){
    	return $this->belongsTo(MetaCliente::class,'meta_cliente_id');
    }

    public function scopeBuscar($query, $documento){
        if (!empty($documento)) {
            $query->whereHas('cliente', function ($query) use ($documento){
                $query->where('Clientes.Identificacion', $documento);            
            });
        }        
    }
    
}
