<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PqrArchivo extends Model
{
    protected $table = 'ClientesPqrsArchivos';
    protected $primaryKey = 'PqrArcId';
    public $timestamps = false;
    
    protected $fillable = ['PqrId','FileContent','FileName','Comentario','Cun','RePqrId','Tipo','ParadaId','Secuencia','Enlace','Esfoto', 'ruta', 'tipo_archivo'];

    public function pqr(){
        return $this->belongsTo(PQR::class, 'PqrId');
    }
}
