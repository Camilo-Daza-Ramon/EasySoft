<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcuerdoPago extends Model
{
    //
    protected $table = 'acuerdos_pago';

    protected $fillable = [
        'valor_deuda',
        'total_cuotas',
        'valor_perdonar',
        'tipo_descuento',
        'descuento',
        'descripcion',
        'estado',
        'cliente_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function cuotas_acuerdo()
    {
        return $this->hasMany(CuotaAcuerdoPago::class ,'acuerdo_id');
    }

    public function scopeDocumento($query, $documento){
        if (!empty($documento)) {       
            $query->whereHas('cliente', function ($query) use ($documento){
                $query->where('Clientes.Identificacion', $documento);
            });        
        }
    }

    public function scopeEstado($query , $estado){
        if(!empty($estado)){
            $query->where('estado', $estado);
        }
    }

   
}


