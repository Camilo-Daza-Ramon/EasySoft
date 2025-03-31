<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'Departamentos';
    protected $primaryKey = 'DeptId';
    protected $fillable=['PaisID','CodigoDaneDepartamento','NombreDelDepartamento','Status'];

    public function municipio(){
    	return $this->hasMany(Municipio::class);
    }
}
