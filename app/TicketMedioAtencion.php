<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketMedioAtencion extends Model
{
    //
    protected $table = 'TB_TIPO_ENTRADA_TICKET';
    protected $primaryKey = 'TipoEntradaTicket';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['Descripcion', 'Status'];
    

    public function ticket(){
        return $this->hasMany(Ticket::class, 'TipoDeEntrada');
    }
}
