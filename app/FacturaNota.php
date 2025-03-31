<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaNota extends Model
{
    protected $table = 'facturas_notas';
    protected $fillable = ['tipo_nota','tipo_concepto_id','tipo_operacion_id','tipo_negociacion_id','tipo_medio_pago_id','fecha_expedision','factura_id','reportada','numero_nota_dian','documento_id_feel','valor_total', 'archivo','descuento', 'motivo_descuento'];

    public function factura(){
    	return $this->belongsTo(Facturacion::class, 'factura_id');
    }

    public function tipo_concepto(){
    	return $this->belongsTo(ConceptoFacturacionElectronica::class, 'tipo_concepto_id');
    }

    public function tipo_operacion(){
    	return $this->belongsTo(ConceptoFacturacionElectronica::class, 'tipo_operacion_id');
    }

    public function tipo_negociacion(){
    	return $this->belongsTo(ConceptoFacturacionElectronica::class, 'tipo_negociacion_id');
    }

    public function tipo_medio_pago(){
    	return $this->belongsTo(ConceptoFacturacionElectronica::class, 'tipo_medio_pago_id');
    }

    public function producto(){
        return $this->hasMany(NotaProducto::class);
    }

    public function detalles_feel(){
        return $this->hasMany(NotaResultadoFeel::class, 'factura_nota_id');
    }

    public function scopeCedula($query, $cedula){
        if(!empty($cedula)){
            $query->whereHas('factura', function ($query) use ($cedula){
                $query->whereHas('cliente', function ($query) use ($cedula){
                    $query->where('Clientes.Identificacion', $cedula);
                });
            });
        }
    }

    public function scopeProyecto($query, $proyecto){
        if(!empty($proyecto)){
            $query->whereHas('factura', function ($query) use ($proyecto){
                $query->whereHas('cliente', function ($query) use ($proyecto){
                    $query->where('Clientes.ProyectoId', $proyecto);
                });
            });
        }
    }

    public function scopeTipo($query, $tipo){
        if (!empty($tipo)) {
            $query->where('tipo_nota', $tipo);
        }
    }

    public function scopePeriodo($query, $periodo){
        if (!empty($periodo)) {
            $query->whereHas('factura', function ($query) use ($periodo){
                $periodo = str_replace('-', '', $periodo);
                $query->where('Facturacion.Periodo', $periodo);
            });
        }
    }

}
