<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoClausula extends Model
{
    protected $table = 'proyectos_clausulas';
    protected $fillable = ['numero_mes','valor','proyecto_id'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
