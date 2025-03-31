<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampanaCamposOpciones extends Model
{
    //
    protected $table = 'campanas_campos_opciones';
    protected $fillable = [
        'valor',
        'estado',
        'campo_id'
    ];

    public function campo(){
        return $this->belongsTo(CampanaCampos::class);
    }
}
