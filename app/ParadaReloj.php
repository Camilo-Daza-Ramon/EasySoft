<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParadaReloj extends Model
{
    //

    protected $table = "ClientesPqrParadaReloj";
    protected $primaryKey = 'ParadaId';
    public $timestamps = false;
    protected $fillable= [
        'CUN',
        'Pqrid',
        'InicioParadaDeReloj',
        'FinParadaDeReloj',
        'DescripcionParada',
        'Secuencia',
        'HoraInicio',
        'MinInicio',
        'HoraFin',
        'MinFin',
        'InicioParada',
        'FinParada'
    ];

    public function pqr(){
        return $this->belongsTo(PQR::class, 'Pqrid');
    }
}
