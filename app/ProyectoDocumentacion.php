<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoDocumentacion extends Model
{
    protected $table = "proyectos_documentacion";
    protected $fillable = [
        'nombre',
        'alias',
        'descripcion',
        'tipo',
        'estado',
        'proyecto_id',
        'coordenadas'
    ];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
