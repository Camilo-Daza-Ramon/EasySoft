<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoEquipo extends Model
{
    protected $table = "MantenimientoDeEquipos";
    protected $primaryKey = 'EqId';
    protected $fillable = [
        'Equipo',
        'MarcaReferencia',
        'Serial',
        'RealizoCambio',
        'ProgMantid',
        'MantId',
        'Observaciones',
        'SerialAnterior'
    ];
    public $timestamps = false;

    /*public function mentenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'ProgMantid');
    }*/

    public function mentenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'MantId');
    }

    public function mentenimiento_preventivo(){
        return $this->belongsTo(MantenimientoPreventivo::class, 'ProgMantid');
    }

    


}
