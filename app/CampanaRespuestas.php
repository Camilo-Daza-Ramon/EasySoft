<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampanaRespuestas extends Model
{
    //
    protected $table = 'campanas_respuestas';
    protected $fillable = [ 
        'respuesta',
        'campo_id', 
        'campana_cliente_id',
        'usuario_id'        
    ];
    public function cliente()
    {
        return $this->belongsTo(CampanaClientes::class ,'campana_cliente_id');
    }
    public function campo()
    {
        return $this->belongsTo(CampanaCampos::class, 'campo_id');
    }

    public function usuario() 
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
   

}
