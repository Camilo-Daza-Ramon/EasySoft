<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentalArchivo extends Model
{
    protected $table = 'documental_archivos';
    protected $fillable = ['nombre', 'ruta', 'tipo', 'documental_version_id'];

    public function version(){
        return $this->belongsTo(DocumentalVersion::class, 'documental_version_id');
    }
}
