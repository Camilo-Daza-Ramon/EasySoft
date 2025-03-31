<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'InsumosBasicos';
    protected $primaryKey = 'InsumoId';
    protected $fillable = [
        'ListaOrigen',
        'Codigo',
        'Descripcion',
        'InsumoTipo',
        'GrupoInsumosId',
        'UnidadCompra',
        'UnidadUso',
        'ValorUnitario',
        'PresentacionCompra',
        'CantidadUso',
        'FactordeConversion',
        'ValorUnitarioUnidadCompra',
        'FechaActualizacionPrecio',
        'CodigoEquivalente',
        'Comentario',
        'Marca',
        'Referencia',
        'ubicacion',
        'PrecioUS',
        'TipoTecnologia',
        'FileContent',
        'FileName',
        'Iva',
        'EsActivo'];

    public function activo_fijo(){
    	return $this->hasMany(ActivoFijo::class, 'InsumoId');
    }

    public function scopeBuscar($query, $palabra){
    	if (!empty($palabra)) {
    		$query->where('Descripcion', 'like', '%' . $palabra . '%')
                ->orWhere(function ($query) use($palabra) {
                    $query->whereHas('activo_fijo', function ($query) use ($palabra){
                        $query->where('ActivosFijos.Serial', $palabra);
                    });
                });
    	}
    	
    }
}
