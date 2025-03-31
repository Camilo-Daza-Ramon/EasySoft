<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    protected $table = 'Facturacion';

    protected $primaryKey = 'FacturaId';
    public $timestamps = false;

    protected $fillable = [
    	'FacturaNumero',
    	'FacturaId', 
    	'ClienteId', 
    	'Periodo', 
    	'Internet', 
    	'Antivirus', 
    	'Telefonia', 
    	'Tv',  
    	'Otro', 
    	'Iva', 
    	'NotaCredito', 
    	'AjustesPorFaltaDeServicio', 
    	'AjusteAlPeso', 
    	'ValorRecaudo', 
    	'Traslado', 
    	'Saldo', 
    	'SaldoEnMora', 
    	'ValorTotal', 
    	'Mes', 
    	'Año', 
    	'MesFacturado',  
    	'AñoFacturado', 
    	'ProyectoId', 
    	'UbicacionId', 
    	'NombreCliente', 
    	'Direccion', 
    	'FechaEmision', 
    	'Municipio', 
    	'DiasDescontados', 
    	'HorasSinServicio', 
    	'Meta', 
    	'Concepto',  
    	'Identificacion', 
    	'EmpresaFacturaId', 
    	'FechaInstalacion', 
    	'NombreUbicacion', 
    	'Pago', 
    	'Ciudad', 
    	'CorreoElectronico', 
    	'CodigoDane', 
    	'PeriodoFacturado', 
    	'PagoConDescuento', 
    	'FechaDePago', 
    	'FechaDePagoConDescuento', 
    	'ResolucionId', 
    	'PeriodoServicio', 
    	'ValorCuota',
        'saldo_favor',
        'plan_contratado',
        'descripcion_plan',
        'tipo_facturacion',
        'ultimo_pago',
        'fecha_ultimo_pago',
        'estado'
    ];

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function factura_electronica(){
        return $this->hasOne(FacturaElectronica::class, 'FacturaId');
    }

    public function nota(){
        return $this->hasMany(FacturaNota::class, 'factura_id');
    }

    public function item(){
        return $this->hasMany(FacturaItem::class, 'factura_id')->orderBy('valor_total', 'DESC');
    }

    public function factura_novedad(){
        return $this->hasMany(FacturaNovedad::class, 'factura_id');
    }

    public function proyecto(){
    	return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }

    
    public function scopeBuscar($query, $palabra){
        if (!empty($palabra)) {
            $query->where("Facturacion.FacturaId", $palabra)->orWhere('Facturacion.Identificacion', $palabra)->orWhere(function($query) use($palabra){
                    $query->whereHas('factura_electronica', function ($query) use ($palabra){
                        $query->where('facturas_electronicas.numero_factura_dian', $palabra);
                });
            });
        }        
    }    


    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->where('Facturacion.ProyectoId', $proyecto);
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
                $query->whereHas('municipio', function ($query) use ($departamento){
                    $query->where('DeptId', $departamento);
                });
            });
        }        
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->whereHas('cliente', function ($query) use ($municipio){
                $query->where('Clientes.municipio_id', $municipio);
            });
        }        
    }

    public function scopeBuscarestadofe($query, $estado){
        if (!empty($estado)) {
            $query->whereHas('factura_electronica', function ($query) use ($estado){
                $query->where('facturas_electronicas.reportada', $estado);
            });
        }        
    }

}
