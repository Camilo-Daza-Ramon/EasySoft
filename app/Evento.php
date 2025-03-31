<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'TB_TIPO_EVENTO';
    protected $fillable = ['TipoEvento'];
    protected $primaryKey = 'IdTipoEvento';

    public function pqr(){
        return $this->hasMany(pqr::class, 'TipoDeEvento');
    }
}
