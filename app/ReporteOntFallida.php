<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReporteOntFallida extends Model
{
    protected $table = 'reportes_onts_fallidas';
    protected $fillable = [
        'ClienteId',
        'ONT_Serial',
        'mensaje',
        'Identificacion'
    ];


    public function scopeBuscarPorSerial($query, $serial)
    {
        if ($serial !== null) {
            $query->where('ONT_Serial', '=', $serial);
        }
    }

    public function scopeBuscarPorCedula($query, $cedula)
    {
        if ($cedula !== null && is_numeric($cedula)) {
            $query->orWhere('Identificacion', '=', $cedula);
        }
    }
}
