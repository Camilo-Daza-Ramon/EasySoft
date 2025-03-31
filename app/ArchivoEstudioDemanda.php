<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoEstudioDemanda extends Model
{
    protected $table = 'archivos_estudios_demanda';
    protected $fillable = ['nombre', 'archivo','tipo', 'estudio_demanda_id'];

    public function estudio_demanda(){
    	return $this->belongsTo(EstudioDemanda::class);
    }
}
