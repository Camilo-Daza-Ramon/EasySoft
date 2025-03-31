<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaNovedad extends Model
{
    protected $table = 'facturas_novedades';
    protected $fillable = ['factura_id', 'novedad_id'];
    public $timestamps = false;

    public function factura(){
        return $this->belongsTo(Facturacion::class, 'factura_id','FacturaId');
    }

    public function novedad(){
    	return $this->belongsTo(Novedad::class);
    }
}
