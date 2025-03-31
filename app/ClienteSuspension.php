<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteSuspension extends Model
{
    protected $table = "clientes_suspensiones";
    protected $fillable = ['tipo','fecha_inicio','cliente_id','user_id'];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

}
