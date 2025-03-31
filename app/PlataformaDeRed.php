<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlataformaDeRed extends Model
{
    protected $table = 'plataformas_de_red';
    public $timestamps = true;
    protected $fillable = [
        "nombre",
        "link",
        "instruccion_id",
        "dato_acceso_id",
        "proyecto_id"
    ];

    public function instruccion() {
        return $this->belongsTo(PlataformaRedInstruccion::class, 'instruccion_id');
    }

    public function acceso() {
        return $this->belongsTo(PlataformaRedAcceso::class, 'dato_acceso_id');
    }

    public function proyecto() {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'ProyectoID');
    }

    public function municipios() {
        return $this->belongsToMany(Municipio::class, 'plataformas_municipios', 'plataforma_id', 'municipio_id');
    }

    public function scopeBuscarPorProyecto($query, $proyecto_id) {
        if ($proyecto_id != 0 && !empty($proyecto_id)) {
            $query->where('proyecto_id', '=', $proyecto_id);
        }
    }

    public function scopeBuscarPorDepartamento($query, $departamento_id) {
        if ($departamento_id != 0 && !empty($departamento_id)) {
            $query->orWhereHas('municipios', function ($q) use($departamento_id) {
                $q->orWhere('DeptId', '=', $departamento_id);
            });
        }
    }

    public function scopeBuscarPorMunicipio($query, $municipio_id) {
        if ($municipio_id != 0 && !empty($municipio_id)) {
            $query->whereHas('municipios', function ($q) use($municipio_id) {
                $q->where('MunicipioId', '=', $municipio_id);
            });
        }
    }
}
