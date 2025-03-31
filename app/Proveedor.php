<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = "proveedores";
    protected $fillable = [
        'nombre',
        'tipo_identificacion',
        'identificacion',
        'tipo',
        'direccion',
        'municipio_id',
        'estado',
        'telefono',
        'celular',
        'correo_electronico'
    ];

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}
