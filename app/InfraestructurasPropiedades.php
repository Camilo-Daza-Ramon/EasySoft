<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfraestructurasPropiedades extends Model
{
    protected $table = 'infraestructuras_propiedades';
    protected $fillable = [
        'infraestructura_id',
        'nombre',
        'valor',
        'unidad_medida'
    ];

}
