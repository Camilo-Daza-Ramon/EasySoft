<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recaudo extends Model
{
    protected $table = 'ClientesRecaudos';
    protected $primaryKey = 'RecaudoId';
    public $timestamps = false;
    protected $fillable = [
                        'valor',
                        'Fecha',
                        'cedula',
                        'nombres',
                        'apellido1',
                        'apellido2',
                        'campo4',
                        'campo5',
                        'ClienteId',
                        'Periodo',
                        'Referencia',
                        'RecaudoId',
                        'FechaOriginal',
                        'RecaudadoPor',
                        'user_id'
                    ];

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {

            if(is_numeric($cedula)){
                $query->whereHas('cliente', function ($query) use ($cedula){
                    $query->where('Clientes.Identificacion', $cedula);
                });
            }else{
                $query->orWhere('Referencia', $cedula);
            }
        }
    }

    public function scopeFechas($query,$fecha_desde, $fecha_hasta){
    	if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    		$query->whereBetween('Fecha', [$fecha_desde, $fecha_hasta]);
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

    public function scopeInconsistencia($query,$inconsistencia){
        if (!empty($inconsistencia)) {
            $query->whereNull('ClienteId')->whereNotIn('RecaudoId', [210804]);
        }
    }

    public function scopeEntidad($query,$medio_pago){
    	if (!empty($medio_pago)) {
    		$query->where('RecaudadoPor', $medio_pago);
    	}
    }
}
