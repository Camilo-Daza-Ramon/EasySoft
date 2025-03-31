<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtencionCliente extends Model
{
    protected $table = 'atencion_clientes';
    protected $fillable = [
        'identificacion',
        'identificacion_titular',
        'nombre',
        'cliente_id',
        'motivo_atencion_id',
        'descripcion',
        'solucion',
        'fecha_atencion_agente',
        'estado',
        'user_id',
        'medio_atencion',
        'municipio_id',
        'codigo',
        'ticket_id',
        'mantenimiento_id',
        'pqr_id'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function motivo_atencion(){
        return $this->belongsTo(MotivoAtencion::class);
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function punto_atencion_cliente(){
        return $this->hasOne(PuntoAtencionCliente::class,'atencion_cliente_id');
    }

    public function respuesta(){
        return $this->hasMany(RespuestaEncuestaCliente::class, 'atencion_cliente_id');
    }

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
        return $this->hasOne(Solicitud::class,'atencion_cliente_id');
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->where('identificacion', $cedula);          
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('municipio', function ($query) use ($departamento){
                $query->where('Municipios.DeptId', $departamento);
            });
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->where('municipio_id', $municipio);
            
        }
    }

    public function scopeCategoria($query, $categoria){
        if (!empty($categoria)) {
            $query->whereHas('motivo_atencion', function ($query) use ($categoria){
                $query->where('motivos_atencion.categoria', $categoria);
            });
        }
    }

    public function scopeMotivo($query, $motivo){
        if (!empty($motivo)) {
            $query->where('motivo_atencion_id', $motivo);          
        }
    }

    public function scopeEstado($query, $estado){
        if (!empty($estado)) {
            $query->where('estado',  $estado );
        }
    }
}
