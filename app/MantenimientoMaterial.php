<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoMaterial extends Model
{
    protected $table = 'MantenimientosInsumos';
    protected $primaryKey = 'MaterialMantEqId';
    public $timestamps = false;
    protected $fillable = ['InsumoId','Unidad','Descripcion','MantEqId','MantId','Cantidad','Serial'];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'MantId');
    }

    public function inventario(){
        return $this->belongsTo(Insumo::class,'InsumoId');
    }
    


}
