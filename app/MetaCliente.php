<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaCliente extends Model
{
    protected $table = 'metas_clientes';
    protected $fillable = ['ClienteId', 'meta_id', 'idpunto'];

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function reemplazo(){
        return $this->hasOne(ClienteReemplazo::class, 'meta_cliente_id');
    }

    public function meta(){
    	return $this->belongsTo(Meta::class);
    }

    public function scopeBuscar($query, $documento){
        if (!empty($documento)) {
            $query->whereHas('cliente', function ($query) use ($documento){
                $query->where('Clientes.Identificacion', $documento);
            })->orWhereHas('reemplazo', function ($query) use ($documento){
                $query->whereHas('cliente', function ($query) use ($documento){
                    $query->where('Clientes.Identificacion', $documento);
                });
            });
        }
    }

    public function scopeProyecto($query, $proyecto){
        if(!empty($proyecto)){
            $query->whereHas('meta', function ($query) use ($proyecto){
                $query->where('ProyectoID', $proyecto);
            });
        }
    }
}
