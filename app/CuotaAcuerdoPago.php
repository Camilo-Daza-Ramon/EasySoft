<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuotaAcuerdoPago extends Model
{
    //
    protected $table = 'cuotas_acuerdo_pago';
    protected $fillable = [
        'cuota',
        'valor_pagar',
        'fecha_pago',
        'estado',
        'acuerdo_id'
    ];

    public function acuerdo()
    {
        return $this->belongsTo(AcuerdoPago::class, 'acuerdo_id');
    }
   
}
