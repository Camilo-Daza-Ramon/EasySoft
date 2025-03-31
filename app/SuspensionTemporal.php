<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuspensionTemporal extends Model
{
    protected $table = "suspensiones_temporales";
    protected $fillable = [
        'descripcion',
        'cliente_id',
        'user_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'fecha_solicitud',
        'estado',
        'novedad_id',
        'solicitud_id'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id')->select('ClienteId', 'Identificacion', 'NombreBeneficiario', 'Apellidos');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function novedad(){
        return $this->belongsTo(Novedad::class, 'novedad_id');
    }

    public function solicitud(){
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function scopeEstado($query, $estado){
        if(!empty($estado)){
            return $query->where('estado', $estado);
        }
    }

    public function scopeCedula($query, $cedula){
        if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($cedula){
                $query->where('Cñientes.Identificacion', $cedula);
            });
        }
    }
    
}
