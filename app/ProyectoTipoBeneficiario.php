<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoTipoBeneficiario extends Model
{
    protected $table = "proyectos_tipos_beneficiarios";
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'proyecto_id'
    ];


    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    
}
