<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoArchivo extends Model
{
    protected $table = 'mantenimientos_archivos';
    protected $fillable = [
        'nombre',
        'archivo',
        'tipo_archivo',
        'mantenimiento_id', 
        'mantenimiento_preventivo_id'
    ];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'mantenimiento_id');
    }

    public function mantenimiento_preventivo(){
        return $this->belongsTo(MantenimientoPreventivo::class, 'mantenimiento_preventivo_id');
    }




    /*protected $table = 'MantenimientosFotos';
    protected $primaryKey = 'MantFotoId';
    protected $fillable = ['FileName','Descripcion','Mantid','MantEqId','MantFotoId','ProgMantId','Tipo','Enlace','Foto','NombreFoto','EnlaceFisico','NumeroDeTicket','Ext','EnDirectorio'];
    public $timestamps = false;

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'Mantid');
    }*/
}
