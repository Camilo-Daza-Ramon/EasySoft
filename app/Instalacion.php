<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instalacion extends Model
{
    protected $table = 'instalaciones';
    protected $fillable = [
        'ClienteId',
        'serial_ont',
        'tipo_conexion',
        'marca_equipo',
        'serial_equipo',
        'estado_equipo',
        'cantidad_equipos_conectados',
        'tipo_conexion_electrica',
        'tipo_proteccion_electrica',
        'marca_proteccion_electrica',
        'serial_proteccion_electrica',
        'estado_conexion_electrica',
        'velocidad_bajada',
        'velocidad_subida',
        'conector',
        'pigtail',
        'cant_retenciones',
        'tipo_retenciones',
        'cinta_bandit',
        'hebilla',
        'gancho_poste',
        'gancho_pared',
        'cant_correa_amarre',
        'tipo_correa_amarre',
        'cant_chazo',
        'tipo_chazo',
        'tornillo',
        'roseta',
        'patch_cord_fibra',
        'patch_cord_utp',
        'fibra_drop_desde',
        'fibra_drop_hasta',
        'caja',
        'puerto',
        'sp_splitter',
        'ss_splitter',
        'tarjeta',
        'modulo',
        'servicio_activo',
        'cumple_velocidad_contratada',
        'latitud',
        'longitud',
        'observaciones',
        'fecha',
        'estado',
        'user_id',
        'auditor_id',
        'olt',
        'port_onu',
        'fecha_auditado',
        'motivo_rechazo',
        'descripcion_rechazo',
        'activo_fijo_id',
        'infraestructura_id'
    ];

    public function archivo(){
    	return $this->hasMany(InstalacionArchivo::class);
    }

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function tecnico(){
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function auditor(){
    	return $this->belongsTo(User::class, 'auditor_id');
    }

    public function activo_fijo(){
        return $this->belongsTo(ActivoFijo::class, 'activo_fijo_id');
    }

    public function infraestructura(){
    	return $this->belongsTo(Infraestructura::class, 'infraestructura_id');
    }

    /*public function scopeBuscar($query, $documento, $municipio, $estado, $serial){
        if (!empty($documento)) {
            $query->whereHas('cliente', function ($query) use ($documento){
                $query->where('Clientes.Identificacion', $documento);
            });
        }elseif(!empty($serial)){
            $query->where("serial_ont","=", $serial);
        }elseif (!empty($municipio) && !empty($estado)) {
            $query->whereHas('cliente', function ($query) use ($municipio){
                $query->where('Clientes.municipio_id', $municipio);
            })->where('estado',$estado);
        }elseif (!empty($municipio) && empty($estado)) {
            $query->whereHas('cliente', function ($query) use ($municipio){
                $query->where('Clientes.municipio_id', $municipio);
            });
        }elseif(empty($municipio) && !empty($estado)){
            $query->where('estado', $estado);
        }

    }*/

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->whereHas('cliente', function ($query) use ($cedula){
                $query->where('Clientes.Identificacion', $cedula);
            });
        }
    }

    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->whereHas('cliente', function ($query) use ($proyecto){
                $query->where('Clientes.ProyectoId', $proyecto);
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

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
                $query->whereHas('municipio', function ($query) use ($departamento){
                    $query->where('Municipios.DeptId', $departamento);
                });
            });
        }
    }

    public function scopeEstado($query,$estado){
        if (!empty($estado)) {
            $query->where('estado', $estado);
        }
    }

    public function scopeSerial($query,$serial){
        if (!empty($serial)) {
            $query->where('serial_ont', $serial);
        }
    }
}
