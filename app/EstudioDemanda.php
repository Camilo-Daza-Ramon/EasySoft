<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstudioDemanda extends Model
{
    protected $table = 'estudios_demanda';
    protected $fillable = ['nombre', 'version','proyecto_id', 'proyecto_municipio_id', 'user_id'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function proyecto_municipio(){
    	return $this->belongsTo(ProyectoMunicipio::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function archivos(){
    	return $this->hasMany(ArchivoEstudioDemanda::class);
    }

    public function ScopeBuscar($query, $nombre){
    	if (!empty($nombre)) {
    		$query->where('nombre', 'like', '%' . $nombre. '%');
    	}    	
    }

    public function ScopeDepartamento($query, $nombre){
    	if (!empty($nombre)) {
    		$query->where('nombre', 'like', '%' . $nombre. '%');
    	}
    }

    public function ScopeMunicipio($query, $id){
    	if (!empty($id)) {
    		$query->where('proyecto_municipio_id', $id);
    	}    	
    }
}
