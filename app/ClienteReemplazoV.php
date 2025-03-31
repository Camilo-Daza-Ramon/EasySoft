<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteReemplazoV extends Model
{
    protected $table = 'clientes_reemplazosV';
    protected $fillable = ['ClienteId','Identificacion','nombre','municipio_id','ProyectoId','NombreMunicipio','NombreDepartamento','META','Status','reemplazado_por','meta_cliente'];

    public function scopeCedula($query, $documento){
        if (!empty($documento)) {
            $query->where('clientes_reemplazosV.Identificacion', $documento)->orWhere('clientes_reemplazosV.reemplazado_por', $documento);
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->where('clientes_reemplazosV.municipio_id', $municipio);
        }
    }

    public function scopeEstado($query, $estado){
        if (!empty($estado)) {

            if ($estado == 'PENDIENTE') {
                $query->whereNull('clientes_reemplazosV.reemplazado_por');
            }else{
                $query->whereNotNull('clientes_reemplazosV.reemplazado_por');
            }            
        }
    }

    public function scopeMeta($query, $meta){
        if (!empty($meta)) {
            $query->where('META', $meta);         
        }
    }

    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->where('clientes_reemplazosV.ProyectoId', $proyecto);
        }
    }
}
