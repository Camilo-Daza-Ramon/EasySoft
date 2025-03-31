<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTipoPrueba extends Model
{
    protected $table = 'MesaDeAyudaPruebas';
    protected $fillable = ['Prueba', 'Descripcion'];
    protected $primaryKey = 'PruebaId';
}
