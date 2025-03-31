<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleFacturaElectronica extends Model
{
    protected $table = 'detalles_facturas_electronicas';
    protected $fillable = ['factura_electronica_id', 'fecha', 'concepto', 'detalles'];
    public $timestamps = false;

    public function factura_electronica(){
    	return $this->belongsTo(FacturaElectronica::class);
    }
}
