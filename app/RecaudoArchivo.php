<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecaudoArchivo extends Model
{
    protected $table = 'ClientesRecaudosArchivos';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'archivo'
    ];
}
