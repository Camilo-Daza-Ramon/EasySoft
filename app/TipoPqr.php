<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPqr extends Model
{
    protected $table = "TB_TIPO_PQR";
    protected $primaryKey = 'TipologiaPqr';

    protected $fillable = [
        'ClasificacionPqr',
        'Descripcion',
        'Prioridad'
    ];
}
