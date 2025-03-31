<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestaEncuestaTotalV extends Model
{
    protected $table = 'respuestas_encuestas_totalesV';
    protected $fillable = ['id','cedula','pregunta','respuesta','fecha','identificador','telefono','tipo'];

    public function encuesta(){
        return $this->belongsTo(EncuestaSatisfaccion::class, 'pregunta');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cedula', 'Identificacion');
    }

    public function atencion(){
        return $this->belongsTo(AtencionCliente::class, 'identificador');
    }

    public function scopeBuscar($query, $palabra){
        if(!empty($palabra)){
            $query->where('cedula', $palabra)
            ->orWhereHas('cliente', function($query) use($palabra){
                $query->where('identificacion', $palabra);
            })
            ->orWhere('telefono', $palabra)
            ->orWhere('identificador', $palabra);
        }
    }

    public function scopeFecha($query, $desde, $hasta){

        if (!empty($desde) && !empty($hasta)) {
            $query->whereBetween('fecha', [$desde, $hasta]);
        }elseif(!empty($desde) && empty($hasta)){
            $query->where('fecha', '>=', $desde);
        }
    }

    public function scopeTipo($query, $tipo){
        if(!empty($tipo)){
            $query->where('tipo', $tipo);
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->whereHas('cliente', function ($query) use ($municipio){
                $query->where('municipio_id', $municipio);
            });
        }
    }

    /*public function scopeTelefono($query, $telefono){
        if(!empty($telefono)){
            $query->where('telefono', $telefono);
        }
    }

    public function scopeIdentificador($query, $identificador){
        if(!empty($identificador)){
            $query->where('identificador', $identificador);
        }
    }*/
}
