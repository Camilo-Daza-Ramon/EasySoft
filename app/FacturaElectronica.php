<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaElectronica extends Model
{
	public $timestamps = false;
    protected $table="facturas_electronicas";
    protected $fillable = ['reportada', 'numero_factura_dian', 'FacturaId', 'documento_id_feel', 'archivo'];

    public function facturacion(){
    	return $this->belongsTo(Facturacion::class);
    }

    public function detalles_factura_electronica(){
    	return $this->hasMany(DetalleFacturaElectronica::class);
    }
}
