<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ProyectosUbicaciones';
    protected $primaryKey = 'UbicacionId';
    protected $fillable = ['ProyectoId', 'MunicipioId'];

    public function municipio(){
    	return $this->belongsTo(Municipio::class, 'MunicipioId');
    }

    public function cliente(){
    	return $this->hasMany(Cliente::class);
    }
}
