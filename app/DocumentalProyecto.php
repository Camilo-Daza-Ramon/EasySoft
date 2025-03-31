<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentalProyecto extends Model
{
    protected $table = 'documental_proyectos';
    protected $fillable = ['nombre', 'tipo', 'proyecto_id', 'documental_carpeta_id'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'ProyectoID');
    }

    public function carpeta(){
        return $this->belongsTo(DocumentalCarpeta::class, 'documental_carpeta_id');
    }

    public function versiones(){
        return $this->hasMany(DocumentalVersion::class, 'documental_proyecto_id');
    }

    public function mensuales(){
        return $this->hasMany(DocumentalMensual::class, 'documental_proyecto_id');
    }

}
