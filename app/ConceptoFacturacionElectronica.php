<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConceptoFacturacionElectronica extends Model
{
    protected $table = 'conceptos_facturacion_electronica';
    protected $fillable = ['nombre','codigo','tipo'];
    public $timestamps = false;
}
