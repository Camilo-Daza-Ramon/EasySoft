<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoCliente extends Model
{
    protected $table = 'MantenimientoProgramacionClientes';
    protected $primaryKey = 'PrManCKiD';
    protected $fillable = ['Identificacion', 'ClienteId','ProgMantId','Mantid'];
    public $timestamps = false;

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'Mantid');
    } 
    
    public function preventivo(){
        return $this->belongsTo(MantenimientoPreventivo::class, 'ProgMantId');
    }

}
