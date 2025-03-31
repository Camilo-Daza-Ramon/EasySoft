<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoTicket extends Model
{
    protected $table = 'TB_ESTADO_TICKET';
    protected $primaryKey = 'EstadoTicket';
    protected $fillable = ['EstadoTicket','Descripcion'];

    public function ticket(){
    	return $this->hasMany(Ticket::class);
    }
}
