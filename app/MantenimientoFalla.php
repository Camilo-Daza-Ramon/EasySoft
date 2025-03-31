<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoFalla extends Model
{
    protected $table = 'MantenimientosFallas';
    protected $primaryKey = 'MantFallaId';
    public $timestamps = false;
    protected $fillable = ['TipoFallaId','Observacion','MantId','UserId'];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'MantId');
    }

    public function tipo(){
        return $this->belongsTo(TipoFallo::class, 'TipoFallaId');
    }
}
