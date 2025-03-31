<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
    protected $table = 'novedades';
    protected $fillable = [
        'concepto', 
        'cantidad', 
        'valor_unidad',
        'iva',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'ClienteId',
        'cobrar',
        'user_id',
        'ticket_id',
        'unidad_medida', 
        'mantenimiento_id'
    ];

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function factura_novedad(){
    	return $this->hasMany(FacturaNovedad::class);
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class,'ticket_id');
    }

    public function scopeConcepto($query,$concepto){
        if (!empty($concepto)) {
            $query->where('concepto', '=', $concepto);
        }
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->whereHas('cliente', function ($query) use ($cedula){
                $query->where('Clientes.Identificacion', $cedula);
            });
        }
    }

    public function scopeFechas($query,$fecha_inicio, $fecha_fin){
        if (!empty($fecha_inicio) && !empty($fecha_fin)){
            $query->whereBetween('fecha_inicio', [$fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59']);
        }elseif (!empty($fecha_inicio) && empty($fecha_fin)){
            $query->whereBetween('fecha_inicio', [$fecha_inicio . ' 00:00:00', date('Y-m-d H:i:s')]);
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

            if ($estado == 'SIN FINALIZAR') {
                $query->whereNull('fecha_fin');
            }elseif($estado == 'FINALIZADA'){
                $query->whereNotNull('fecha_fin');
            }else{
                $query->where('estado', $estado);
            }
            
        }
    }


}
