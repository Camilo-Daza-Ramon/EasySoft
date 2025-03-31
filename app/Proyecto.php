<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{    
    protected $table = 'Proyectos';
    protected $primaryKey = 'ProyectoID';
    public $timestamps = false;
    protected $fillable = [
        'NumeroDeProyecto',
        'DescripcionProyecto',
        'Entidad',
        'Status',
        'Descripcion',
        'ListaDeInsumos',
        'NumeroDeContrato',
        'EmpresaId',
        'Prefijo',
        'vigencia',
        'tipo_facturacion',
        'limite_meses_mora',
        'porcentaje_interes_mora',
        'clausula_permanencia',
        'dia_corte_facturacion',
        'condiciones_plan',
        'condiciones_servicio',
        'fecha_fin_proyecto',
        'acta_juramentada'
    ];


    public function municipio(){
        return $this->belongsToMany(Municipio::class, 'proyectos_municipios', 'proyecto_id', 'municipio_id');
    }

    public function proyecto_municipio(){
        return $this->hasMany(ProyectoMunicipio::class,'proyecto_id');
    }

    public function punto_atencion(){
        return $this->hasMany(PuntoAtencion::class, 'proyecto_id');
    }

    public function cliente(){
    	return $this->hasMany(Cliente::class, 'ProyectoId');
    }

    public function facturacion_api(){
    	return $this->hasOne(FacturacionElectronicaAPI::class, 'proyecto_id');
    }

    public function meta(){
        return $this->hasMany(Meta::class, 'ProyectoId');
    }

    public function plan_comercial(){
        return $this->hasMany(PlanComercial::class, 'ProyectoId');
    }

    public function clausula(){
        return $this->hasMany(ProyectoClausula::class, 'proyecto_id');
    }

    public function costo(){
        return $this->hasMany(ProyectoCosto::class,'proyecto_id');
    }


    public function mantenimiento(){
        return $this->hasMany(Mantenimiento::class,'ProyectoId');
    }

    public function tipos_beneficiarios(){
        return $this->hasMany(ProyectoTipoBeneficiario::class, 'proyecto_id');
    }

    public function documentacion(){
        return $this->hasMany(ProyectoDocumentacion::class, 'proyecto_id');
    }

    public function documental(){
        return $this->hasMany(DocumentalProyecto::class, 'proyecto_id');
    }

    public function preguntas(){
        return $this->hasMany(ProyectoPregunta::class, 'proyecto_id');
    }

    public function respuestas(){
        return $this->hasMany(ProyectoPreguntaRespuesta::class ,'proyecto_id');
    }

    public function carpetas(){
        return $this->hasMany(DocumentalCarpeta::class ,'proyecto_id');
    }

    public function scopeNombre($query,$nombre){
        if (!empty($nombre)) {
            $query->where('NumeroDeProyecto', 'like', '%'.$nombre.'%')->orWhere('DescripcionProyecto', 'like', '%'.$nombre.'%');
        }
    }

    public function scopeContrato($query,$contrato){
        if (!empty($contrato)) {
            $query->where('NumeroDeContrato', 'like', '%'.$contrato.'%');
        }
    }

    public function scopeEstado($query,$estado){
        if (!empty($estado)) {
            $query->where('Status', '=', $estado);
        }
    }
   
}
