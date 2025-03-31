<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campana extends Model
{
    //
    protected $table = 'campanas';
    protected $fillable = [
        'nombre',
        'tipo',
        'estado',
        'periodo_facturacion', 
        'fecha_inicio',
        'fecha_finalizacion',
        'cuotas_max_acuerdo', 
        'valor_pardonar_acuerdo',
        'acuerdo_porcentual',
        'tipo_descuento',
        'sin_restricciones'
    ];

    public function campos(){
        return $this->hasMany(CampanaCampos::class);
    }
    
    public function clientes()
    {
        return $this->hasMany(CampanaClientes::class);
    }

    public function campos_visualizar()
    {
        return $this->hasMany(CamposVisualizar::class);
    }
    

    public function scopeNombre($query, $nombre){
        if (!empty($nombre)) {       
            $query->Where('nombre','like', '%'.$nombre.'%');                      
        }
    }
    public function scopeTipo($query, $tipo){
        if (!empty($tipo)) {       
            $query->Where('tipo', $tipo);
        }
    }
    public function scopeMes($query, $mes){
        if (!empty($mes)) {       
            $query->Where('fecha_inicio','like', $mes.'-__');                      
        }
    }

    public function scopeEstado($query, $estado){
        if (!empty($estado)) {
            $query->where('estado',  $estado );
        }
    }
}
