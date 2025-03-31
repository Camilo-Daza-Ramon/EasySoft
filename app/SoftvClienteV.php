<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoftvClienteV extends Model
{
    protected $table = 'softv_clientesV';
    protected $fillable = ['ClienteId','contrato','cedula', 'Identificacion', 'id_punto','id_softv'];

    public function cliente(){
    	$this->belongsTo(Cliente::class,'ClienteId');
    }
}
