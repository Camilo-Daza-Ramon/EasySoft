<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentalVersion extends Model
{
    protected $table = 'documental_versiones';
    protected $fillable = ['titulo', 'version', 'documental_proyecto_id', 'estado', 'documental_mensual_id'];

    public function documental(){
        return $this->belongsTo(DocumentalProyecto::class, 'documental_proyecto_id');
    }

    public function mensual(){
        return $this->belongsTo(DocumentalProyecto::class, 'documental_mensual_id');
    }

    public function archivos(){
        return $this->hasMany(DocumentalArchivo::class, 'documental_version_id');
    }
}
