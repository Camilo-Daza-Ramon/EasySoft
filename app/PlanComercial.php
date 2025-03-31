<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanComercial extends Model
{
    protected $table = 'PlanesComerciales';
    protected $primaryKey = 'PlanId';
    public $timestamps = false; 
    protected $fillable = [
        'PlanId',
        'ProyectoId',
        'DescripcionPlan',
        'Diferido',
        'Estrato',
        'VelocidadInternet',
        'ValorDelServicio',
        'Telefonia',
        'Televisión',
        'Otro',
        'ValorOtro',
        'PrimerosMeses',
        'DespuesDelMes',
        'ValorInstalacion',
        'Iva',
        'ValorReconexion',
        'MesesGratis',
        'Status',
        'TipoDePago',
        'Observaciones',
        'DiasParaCorte',
        'InteresDeMora',
        'AplicaTarifaSocial',
        'BeneficioTarifaInternet',
        'PC',
        'Leyenda1',
        'Leyenda2',
        'Leyenda3',
        'Leyenda4',
        'Leyenda5',
        'Leyenda6',
        'Leyenda7',
        'TipoDePlan', 
        'nombre'
    ];

    public function cliente(){
    	return $this->hasMany(Cliente::class, 'PlanComercial');
    }

    public function proyecto_municipio(){
        return $this->belongsToMany(ProyectoMunicipio::class, 'planes_municipios', 'plan_comercial_id');
    }
}
