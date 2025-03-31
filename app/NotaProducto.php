<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaProducto extends Model
{
    protected $table = 'notas_productos';
    protected $fillable = ['concepto','cantidad','valor_unidad','iva','valor_iva','valor_total','factura_nota_id'];
}
