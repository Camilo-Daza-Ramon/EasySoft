<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstalacionArchivo extends Model
{
    protected $table = 'instalaciones_archivos';
    protected $fillable = ['nombre', 'archivo', 'tipo_archivo', 'estado', 'instalacion_id'];


    public function instalacion(){
    	return $this->belongsTo(Instalacion::class);
    }
}
