<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoMunicipioMeta extends Model
{
    protected $table = "proyectos_municipios_metas";
    protected $fillable = ['meta_id','proyecto_municipio_id','total_accesos'];

    public function proyecto_municipio(){
        return $this->belongsTo(ProyectoMunicipio::class, 'proyecto_municipio_id');
    }

    public function meta(){
        return $this->belongsTo(Meta::class);
    }
}
