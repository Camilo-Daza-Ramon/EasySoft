<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratoEvento extends Model
{
    protected $table = 'contratos_eventos';
    protected $fillable = ['accion', 'descripcion','user_id', 'contrato_id'];


    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function contrato(){
    	return $this->belongsTo(ClienteContrato::class);
    }


}
