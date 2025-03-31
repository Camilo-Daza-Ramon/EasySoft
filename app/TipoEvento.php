<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    protected $table = "TB_TIPO_EVENTO";
    protected $primaryKey = "IdTipoEvento";
    protected $fillable = ['TipoEvento'];
    public $timestamps = false;
}
