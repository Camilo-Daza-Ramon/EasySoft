<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoMantenimiento extends Model
{
    protected $table = 'TB_TIPOS_MANTENIMIENTO';
    protected $primaryKey = 'TipoDeMantenimiento';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['Descripcion','Status', 'tipo'];

    public function mantenimiento(){
        return $this->hasMany(Mantenimiento::class, 'TipoDeMantenimiento','TipoMantenimiento');
    }
}
