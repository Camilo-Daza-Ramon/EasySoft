<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infraestructura extends Model
{
    protected $table = "infraestructuras";
    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'municipio_id',
        'categoria',
        'tipo_categoria',
        'direccion',
        'datos_ubicacion',
        'descripcion',
        'infraestructura_id',
        'proveedor_id',
        'estado',
    ];
 
    public function municipio(){
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function proveedor() {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function padre() {
        return $this->belongsTo(Infraestructura::class, 'infraestructura_id');
    }

    public function hijos() {
        return $this->hasMany(Infraestructura::class, 'infraestructura_id');
    }

    public function propiedades() {
        return $this->hasMany(InfraestructurasPropiedades::class, 'infraestructura_id');
    }

    public function contactos() {
        return $this->hasMany(InfraestructurasContactos::class, 'infraestructura_id');
    }

    public function proyectos() {
        return $this->belongsToMany(Proyecto::class, 'infraestructuras_proyectos', 'nodo_id', 'proyecto_id');
    }

    public function equipos(){
        return $this->hasMany(InfraestructurasEquipos::class, 'infraestructura_id');
    }

    public function instalacion(){
        return $this->hasOne(Instalacion::class, 'infraestructura_id');
    }

    public function scopeNombre($query, $nombre){
        if (!empty($nombre)) {
            $query->where('infraestructuras.nombre', $nombre); 
        }
    }

    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->whereHas('proyectos', function ($query) use ($proyecto){
                $query->where('Proyectos.ProyectoID', $proyecto);
            });
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('municipio', function ($query) use ($departamento){
                $query->where('Municipios.DeptId', $departamento);
            });
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->where('infraestructuras.municipio_id', $municipio); 
        }
    }    
}
