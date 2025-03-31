<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteOntOlt extends Model
{
    protected $table = 'clientes_onts_olts';
    protected $fillable = ['ClienteId', 'ActivoFijoId', 'olt_id', 'user_id'];

    public function activo(){
        return $this->belongsTo(ActivoFijo::class, 'ActivoFijoId');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function olt(){
        return $this->belongsTo(Olt::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
