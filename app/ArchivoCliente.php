<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoCliente extends Model
{
    protected $table = 'archivos_clientes';

    protected $fillable = ['nombre', 'archivo', 'tipo_archivo', 'estado', 'ClienteId'];

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }
}
