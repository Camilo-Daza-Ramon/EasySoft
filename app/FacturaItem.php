<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaItem extends Model
{
    protected $table = 'facturas_items';
    protected $fillable = ['concepto', 'cantidad','valor_unidad','iva','valor_iva','valor_total','factura_id', 'unidad_medida'];

    public function factura(){
    	return $this->belongsTo(Facturacion::class, 'FacturaId');
    }
}
