<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteContrato extends Model
{
    protected $table = 'clientes_contratos';
    protected $fillable = [
		'referencia', 
		'tipo_cobro', 
		'vigencia_meses', 
		'fecha_inicio', 
		'fecha_instalacion', 
		'fecha_final', 
		'clausula_permanencia', 
		'estado', 
		'vendedor_id', 
		'ClienteId', 
		'archivo', 
		'observacion',
		'fecha_operacion'
	];

    public function vendedor(){
	   return $this->belongsTo(User::class, 'vendedor_id');
	}

	public function cliente(){
		return $this->belongsTo(Cliente::class, 'ClienteId');
	}

	public function servicio(){
		return $this->hasMany(ContratoServicio::class, 'contrato_id');
	}

	public function evento(){
		return $this->hasMany(ContratoEvento::class, 'contrato_id')->orderBy('created_at', 'DESC');
	}

	public function archivos(){
		return $this->hasMany(ContratoArchivo::class, 'contrato_id');
	}

	public function scopeEstado($query, $estado){
		if(!empty($estado)){
			$query->where('estado', $estado);
		}
	}

	public function scopeTipo($query, $tipo){
		if(!empty($tipo)){
			$query->where('tipo_cobro', $tipo);
		}
	}

	public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->whereHas('cliente', function ($query) use ($cedula){
                $query->where('Identificacion', $cedula);
            });
        }        
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
            	$query->whereHas('municipio', function ($query) use ($departamento){
                	$query->where('DeptId', $departamento);
                });
            });
        }        
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->whereHas('cliente', function ($query) use ($municipio){
                $query->where('municipio_id', $municipio);
            });
        }        
    }
}
