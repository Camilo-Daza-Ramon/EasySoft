<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoParadaReloj extends Model
{
    protected $table = 'MantenimientosParadaReloj';
    protected $primaryKey = 'ParadaId';
    public    $timestamps = false;
    protected $fillable = [
        'InicioParadaDeReloj',
        'FinParadaDeReloj',
        'DescripcionParada',
        'HoraInicio',
        'MinInicio',
        'HoraFin',
        'MinFin',
        'InicioParada',
        'FinParada',
        'MantId',
        'ProgMantId'
    ];



    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'MantId');
    }


}
