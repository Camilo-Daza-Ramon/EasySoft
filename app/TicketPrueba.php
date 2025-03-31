<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPrueba extends Model
{
    protected $table = 'ClientesTicketsPruebas';
    protected $fillable = ['TicketId', 'PruebaId','Observacion','Fecha','Hora', 'UserId'];
    protected $primaryKey = 'PruebaTiqId';
    public $timestamps = false;

    public function ticket(){
        return $this->belongsTo(Ticket::class, 'TicketId');
    }

    public function tipo_prueba(){
        return $this->belongsTo(TicketTipoPrueba::class, 'PruebaId');
    }
}
