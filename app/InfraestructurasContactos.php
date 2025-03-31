<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfraestructurasContactos extends Model
{
    protected $table = 'infraestructuras_contactos';
    protected $fillable = [
        'nombre',
        'celular',
        'telefono',
        'cargo_presentativo',
        'infraestructura_id'
    ];
}
