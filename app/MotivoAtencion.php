<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoAtencion extends Model
{
    protected $table = 'motivos_atencion';
    protected $fillable = ['motivo','categoria','estado','solicitud','tiempo_limite','unidad_medida','condicional','observaciones'];
}
