<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteRestriccion extends Model
{
    //
    protected $table = 'clientes_restricciones';
    protected $fillable = [
        'cliente_id',
        'observaciones'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->whereHas('cliente', function ($query) use ($cedula){
                $query->where('Clientes.Identificacion', $cedula);            
            });
        }
    }

}
