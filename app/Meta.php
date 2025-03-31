<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'metas';
    protected $fillable = ['nombre','descripcion','fecha_inicio','fecha_fin','total_accesos','ProyectoID','estado', 'fecha_aprobacion_interventoria', 'fecha_aprobacion_supervision'];

    public function proyecto(){
    	return $this->belongsTo(Proyecto::class, 'ProyectoID');
    }

    public function cliente(){
    	return $this->hasMany(MetaCliente::class);
    }

    public function proyecto_municipio_meta(){
        return $this->hasMany(ProyectoMunicipioMeta::class);
    }
}
