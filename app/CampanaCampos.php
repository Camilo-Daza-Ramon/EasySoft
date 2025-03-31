<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampanaCampos extends Model
{
    //
    protected $table = 'campanas_campos';
    protected $fillable = [
        'nombre',
        'tipo',
        'estado',
        //'obligatorio',
        'campana_id'
    ];

    public function campana()
    {
        return $this->belongsTo(Campana::class);
    }  
    public function respuestas()
    {
        return $this->hasMany(CampanaRespuestas::class ,'campo_id');
    }

    public function opciones(){
        return $this->hasMany(CampanaCamposOpciones::class , 'campo_id');
    }
    
}
