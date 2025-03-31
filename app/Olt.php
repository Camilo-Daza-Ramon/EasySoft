<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
   protected $table = 'olts';
   protected $fillable = ['nombre','ip', 'usuario', 'password', 'latitud', 'longitud', 'municipio_id', 'estado', 'version'];

   public function municipio(){
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function cliente_ont_olt(){
        return $this->hasOne(ClienteOntOlt::class);
    }

    public function scopeBuscar($query,$palabra, $municipio){
        if (!empty($palabra)) {
            $query->where('nombre', 'like', '%'.$palabra.'%');
            
        }elseif (!empty($municipio)) {
            $query->where('municipio_id', $municipio);
        }


    }

    /*public function cliente(){
        return $this->belongsToMany(Cliente::class,'clientes_onts_olts', 'ActivoFijoId')
            ->withPivot('ActivoFijoId');
    }

    public function activo(){
        return $this->belongsToMany(ActivoFijo::class,'clientes_onts_olts', 'ActivoFijoId')
            ->withPivot('ActivoFijoId');
    }*/
}
