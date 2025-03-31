<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoFallo extends Model
{
    protected $table = "TB_TIPOS_FALLO";
    protected $primaryKey = "TipoFallaId";
    protected $fillable = ['DescipcionFallo', 'Uso', 'estado'];
    public $timestamps = false;


    public function ticket(){
    	return $this->hasMany(Ticket::class, 'TipoFallaId');
    }
}
