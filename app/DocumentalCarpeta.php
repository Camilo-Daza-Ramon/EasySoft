<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentalCarpeta extends Model
{
    protected $table = 'documental_carpetas';
    protected $fillable = ['nombre', 'proyecto_id', 'documental_carpeta_id'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'ProyectoID');
    }

    // public function versiones(){
    //     return $this->hasMany(DocumentalVersion::class, 'documental_proyecto_id');
    // }

    // public function mensuales(){
    //     return $this->hasMany(DocumentalMensual::class, 'documental_proyecto_id');
    // }

    public function documental(){
        return $this->hasMany(DocumentalProyecto::class, 'documental_carpeta_id');
    }

    public function subcarpetas(){
        return $this->hasMany(DocumentalCarpeta::class, 'documental_carpeta_id');
    }

    public function carpeta_padre(){
        return $this->belongsTo(DocumentalCarpeta::class, 'documental_carpeta_id');
    }
}
