<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaResultadoFeel extends Model
{
    protected $table = 'notas_resultados_feel';
    protected $fillable = ['factura_nota_id', 'fecha','concepto','detalles'];

    public function factura_nota(){
    	return $this->belonsTo(FacturaNota::class);
    }
}
