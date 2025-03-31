<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoPreguntaRespuesta extends Model
{
    protected $table = "proyectos_preguntas_respuestas";
    protected $fillable = [
        'cliente_id',
        'proyecto_id',
        'proyecto_pregunta_id',
        'respuesta'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function pregunta(){
        return $this->belongsTo(ProyectoPregunta::class, 'proyecto_pregunta_id');
    }

}
