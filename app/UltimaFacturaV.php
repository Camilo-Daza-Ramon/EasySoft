<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UltimaFacturaV extends Model
{
    protected $table = "ultima_facturaV";
    protected $fillable = [
        'cliente_id',
        'periodo',
        'factura_id'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function factura(){
        return $this->belongsTo(Facturacion::class, 'factura_id');

    }
}
