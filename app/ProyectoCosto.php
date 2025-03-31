<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoCosto extends Model
{
    protected $table = 'proyectos_costos';
    protected $fillable = ['concepto','descripcion','iva','valor','proyecto_id'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
