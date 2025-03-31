<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampanaClientes extends Model
{
    //
    protected $table = 'campanas_clientes';
    protected $fillable = [ 
        'estado',
        'campana_id',
        'cliente_id',
        'ticket_id',
        'mantenimiento_id',
        'pqr_id',
        'fecha_hora_rellamar',      
    ];

    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'mantenimiento_id');
    }

    public function pqr(){
        return $this->belongsTo(PQR::class, 'pqr_id');
    }

    public function solicitud(){
        return $this->hasOne(Solicitud::class,'campana_cliente_id');
    }

   
    public function campana()
    {
        return $this->belongsTo(Campana::class);
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
   
    public function respuestas()
    {
        return $this->hasMany(CampanaRespuestas::class ,'campana_cliente_id');
    }
    public function observaciones()
    {
        return $this->hasMany(CampanaObservaciones::class ,'campana_cliente_id');
    }

    public function scopeEstado($query, $estado){
        if (!empty($estado)) {
            $query->where('estado',  $estado );        
        }
    }

    public function scopeCedula($query, $documento){
        if (!empty($documento)) {
            $query->whereHas('cliente', function ($query) use ($documento){
                $query->where('Clientes.Identificacion', $documento);
            });        
        }
    }

    public function scopeBarrio($query, $barrio){
        if (!empty($barrio)) {
            $query->whereHas('cliente', function ($query) use ($barrio){
                $query->where('Clientes.Barrio', $barrio);
            });        
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
                $query->whereHas('municipio', function ($query) use ($departamento){
                    $query->where('Municipios.DeptId', $departamento);
                });
            });
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->whereHas('cliente', function ($query) use ($municipio){
                $query->where('Clientes.municipio_id', $municipio);
            });
        }
    }

    public function scopeMora($query, $mora_desde , $mora_hasta){
        
        if(!empty($mora_desde) or !empty($mora_hasta)){

            $query->join('historial_factura_pagoV', 'campanas_clientes.cliente_id', '=', 'historial_factura_pagoV.ClienteId')
            ->when(is_null($mora_hasta), function ($query) use ($mora_desde) {
                // Ejecutar consulta cuando mora_hasta es nulo
                return $query->where('historial_factura_pagoV.meses_mora', '>=', $mora_desde);
            })
            ->when(!is_null($mora_hasta), function ($query) use ($mora_desde, $mora_hasta) {
                // Ejecutar consulta cuando mora_hasta no es nulo
                return $query->whereBetween('historial_factura_pagoV.meses_mora', [$mora_desde, $mora_hasta]);
            });
        }
    }

}
