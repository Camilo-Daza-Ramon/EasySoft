<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    protected $fillable = [
        'atencion_cliente_id',
        'estado',
        'fecha_hora_solicitud',
        'fecha_limite',
        'fecha_hora_atendida',
        'celular',
        'correo',
        'jornada',
        'user_id',
        'campana_cliente_id',
        'motivo_atencion_id',
        'descripcion',
        'municipio_id'
    ];

    public function atencion(){
        return $this->belongsTo(AtencionCliente::class,'atencion_cliente_id');
    }

    public function campana_cliente(){
      return $this->belongsTo(CampanaClientes::class,'campana_cliente_id');
  }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comentarios(){
      return $this->hasMany(SolicitudComentario::class);
    }

    public function motivo_atencion(){
      return $this->belongsTo(MotivoAtencion::class);
    }

    public function municipio(){
      return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
          $query->whereHas('atencion', function ($query) use ($cedula){
            $query->where('identificacion', $cedula);
          })->orWhereHas('campana_cliente', function($query) use ($cedula){
            $query->whereHas('cliente', function ($query) use ($cedula){
              $query->where('Clientes.Identificacion', $cedula);
            }); 
          });
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

    public function scopeMes($query, $mes){
      if (!empty($mes)) {
        $query->whereBetween('fecha_hora_solicitud',  [$mes."-01 00:00:00" , date('Y-m-t', strtotime($mes)) . " 23:59:59"]);
      }
    }


}
