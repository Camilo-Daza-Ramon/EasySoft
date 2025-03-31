<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentalMensual extends Model
{
    protected $table = 'documental_mensuales';
    protected $fillable = ['periodo','documental_proyecto_id'];

    public function documental(){
        return $this->belongsTo(DocumentalProyecto::class, 'documental_proyecto_id');
    }

    public function versiones(){
        return $this->hasMany(DocumentalVersion::class, 'documental_mensual_id');
    }
}
