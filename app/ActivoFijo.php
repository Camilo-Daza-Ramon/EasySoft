<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    protected $table = 'ActivosFijos';
    protected $primaryKey = 'ActivoFijoId';
    public $timestamps = false;
    protected $fillable = [
        'EmpresaId',
        'UbicacionId',
        'InsumoId',
        'Grupo',
        'SubGrupo',
        'Descripcion',
        'FechaDeAdquisicion',
        'Proveedor',
        'Marca',
        'Serial',
        'Referencia',
        'Modelo',
        'Año',
        'CodigoDeActivo',
        'AñosDepreciar',
        'ValorDeCompra',
        'ValorEnUSA',
        'Cantidad',
        'ValorActualDeMercado',
        'Estado',
        'VidaUtilAnual',
        'PerteneceAlEstado',
        'ClienteId',
        'NombreDelCliente',
        'DireccionDelCliente',
        'TelefonoDelCliente',
        'AlmEntradaId',
        'EstanteId',
        'Prendio',
        'SistemaOperativo',
        'SeConecta',
        'DocumentosEntregaCompletos',
        'Logo',
        'Serigrafia',
        'CodigoDelKit',
        'MAC',
        'AlmacenId'
    ];

    /*public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }*/

    public function cliente_ont_olt(){
        return $this->hasOne(ClienteOntOlt::class, 'ActivoFijoId');
    }


    public function instalaciones(){
        return $this->hasMany(Instalacion::class, 'activo_fijo_id');
    }


    /*public function cliente(){
        return $this->belongsToMany(Cliente::class,'clientes_onts_olts', 'ActivoFijoId', 'ClienteId')
            ->withPivot('ClienteId');
    }

    public function olt(){
        return $this->belongsToMany(Olt::class,'clientes_onts_olts', 'ActivoFijoId')
            ->withPivot('ActivoFijoId');
    }*/

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'InsumoId', 'InsumoId');
    }

    public function scopeBuscar($query, $palabra){
        if (!empty($palabra)) {
            $query->where('Serial',$palabra);
        }
        
    }

    
}
