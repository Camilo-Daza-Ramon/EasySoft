<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoDireccion extends Model
{
    protected $table = "MantenimientoProgramacionDirecciones";
    protected $primaryKey = 'DirId';
    public $timestamps = false;
    protected $fillable = ['Direccion','Barrio','Latitud','Longitud','ProgMantId','MantId'];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'MantId');
    }
}
