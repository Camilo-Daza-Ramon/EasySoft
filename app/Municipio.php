<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    //
    protected $table = 'Municipios';
    protected $primaryKey = 'MunicipioId';
    protected $fillable= ['PaisId','DeptId','CodigoDaneMunicipio','NombreMunicipio','Alcalde','DireccionAlcaldia','Telefonos','Fax','Web','CorreoElectronico','Latitud','Longitud','Latitude','Longitude','Status','NombreDepartamento','CodigoDane', 'region'];

    public function departamento(){
    	return $this->belongsTo(Departamento::class, 'DeptId');
    }

    public function cliente(){
        return $this->hasMany(Cliente::class, 'municipio_id');
    }

    public function ubicacion(){
    	return $this->hasOne(Ubicacion::class);
    }

    public function olt(){
        return $this->hasMany(Olt::class, 'municipio_id');
    }

    public function proyecto_municipio(){
        return $this->hasMany(ProyectoMunicipio::class, 'municipio_id');
    }

}
