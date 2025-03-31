<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlataformaRedInstruccion extends Model
{
    protected $table = 'plataforma_red_instrucciones';
    public $timestamps = true;
    protected $fillable = [
        "nombre",
        "ruta",
        "tipo"
    ];

    public function plataformas_de_red () {
        return $this->hasMany(PlataformaDeRed::class, 'instruccion_id');
    }
}
