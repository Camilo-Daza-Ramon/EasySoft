<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoMunicipio extends Model
{
    protected $table = 'proyectos_municipios';
    protected $fillable = ['proyecto_id', 'municipio_id'];

    public function proyecto(){
    	return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function municipio(){
    	return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function meta(){
        return $this->hasMany(ProyectoMunicipioMeta::class);
    }

    public function plan_comercial(){
        return $this->belongsToMany(PlanComercial::class, 'planes_municipios', 'proyecto_municipio_id', 'plan_comercial_id');
    }

   
}
