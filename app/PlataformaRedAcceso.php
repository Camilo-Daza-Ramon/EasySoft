<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlataformaRedAcceso extends Model
{
    protected $table = 'plataforma_red_accesos';
    public $timestamps = true;
    protected $fillable = [
        "usuario",
        "contrasena",
    ];

    public function plataformas_de_red() {
        return $this->hasMany(PlataformaDeRed::class, 'dato_acceso_id');
    }
}
