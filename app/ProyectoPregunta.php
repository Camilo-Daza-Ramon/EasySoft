<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoPregunta extends Model
{
    protected $table = "proyectos_preguntas";
    protected $fillable = [
        'pregunta',
        'tipo',
        'opciones_respuesta',
        'obligatoriedad',
        'proyecto_id',
        'estado'
    ];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }

    public function respuestas(){
        return $this->hasMany(ProyectoPreguntaRespuesta::class, 'proyecto_pregunta_id');
    }
}
