<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialFacturaPagoV extends Model
{
    protected $table = 'historial_factura_pagoV';
    protected $fillable = ['ClienteId', 'total_deuda'];

    public function cliente(){
    	return $this->BelongsTo(Cliente::class, 'ClienteId');
    }

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
}
