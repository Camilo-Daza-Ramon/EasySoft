<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaAnular extends Model
{
    //
    protected $table = "facturas_anular";
    protected $fillable = [
        'Identificacion',
        'FacturaId',
        'total_descuento'
    ];


    public function items() {
        return $this->hasMany(FacturaItem::class,  'factura_id', 'FacturaId')->where('valor_total', '>', 0)->whereNotIn('concepto', ['Saldo en Mora']);
    }

    public function factura(){
        return $this->belongsTo(Facturacion::class, 'FacturaId');
    }
}
