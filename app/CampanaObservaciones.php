<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampanaObservaciones extends Model
{
    //
    protected $table = 'campanas_observaciones';
    protected $fillable = [ 
        'observacion',
        'campana_cliente_id', 
        'usuario_id'        
    ];
    public function cliente()
    {
        return $this->belongsTo(CampanaClientes::class ,'campana_cliente_id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class ,'usuario_id');
    }
}
