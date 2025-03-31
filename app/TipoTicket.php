<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoTicket extends Model
{
    protected $table = 'TB_TIPO_TICKET';
    protected $fillable = ['Descripcion','Status'];
    protected $primaryKey = 'TipoTicket';

    public function pqr(){
        return $this->hasMany(PQR::class, 'TipoTicket');
    }
}
