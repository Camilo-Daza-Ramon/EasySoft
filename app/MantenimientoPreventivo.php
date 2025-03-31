<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoPreventivo extends Model
{
    protected $table = 'MantenimientoProgramacion';
    protected $primaryKey = 'ProgMantid';
    protected $fallable = [
        'CantClientes',
        'CantidadUsuariosAfectados',
        'ClientesProgramados',
        'Departamento',
        'Fecha',
        'fecha_programada',
        'ProyectoId',
        'Municipio',
        'NumeroDeMantenimiento',
        'estado',
        'Tipo',

        'user_crea',
        'user_cerro',
        'user_atiende',

        'fecha_cierre_hora_inicio',
        'fecha_cierre_hora_fin',        
        
        'IdentificacionDeLaRed',
        'TipoDeTecnologiaImplementada',

        'SeRetornoServicio',
        'ServicioQuedaActivo',

        'VelocidadDeBajada',
        'VelocidadDeSubida',
        
        'Observaciones',
        'ObservacionesHallazgos',
        'Procedimiento',
        
    ];
    public $timestamps = false;

    public function clientes(){
        return $this->hasMany(MantenimientoCliente::class, 'ProgMantId');
    }

    public function archivos(){
        return $this->hasMany(MantenimientoArchivo::class, 'mantenimiento_preventivo_id');
    }

    public function diagnosticos(){
        return $this->hasMany(MantenimientoDiagnostico::class,'ProgMantId');
    }

    public function pruebas(){
        return $this->hasMany(MantenimientoPrueba::class, 'mantenimiento_preventivo_id', 'ProgMantid');
    }

    public function direcciones(){
        return $this->hasMany(MantenimientoDireccion::class,'ProgMantId');
    }

    public function equipos(){
        return $this->hasMany(MantenimientoEquipo::class,'ProgMantid');
    }

    public function paradas_reloj(){
        return $this->hasMany(MantenimientoParadaReloj::class,'ProgMantId');
    }

    public function tipo_mantenimiento(){
        return $this->belongsTo(TipoMantenimiento::class, 'Tipo');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'Municipio');
    }

    public function departamento(){
        return $this->belongsTo(Departamento::class, 'DeptId', 'Departamento');
    }
    
    public function usuario_crea(){
        return $this->belongsTo(User::class, 'user_crea');
    }

    public function usuario_cierra(){
        return $this->belongsTo(User::class, 'user_cerro');
    }

    public function usuario_atiende(){
        return $this->belongsTo(User::class, 'user_atiende');
    }

    public function scopeBuscar($query,$mantenimiento){
        if (!empty($mantenimiento)) {

            if (is_numeric($mantenimiento)) {
                $query->where('ProgMantid', $mantenimiento);
            }else{
                $query->where('NumeroDeMantenimiento', $mantenimiento);
            }
            
        }
    }

    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->where('ProyectoId', $proyecto);
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->where('Municipio', $municipio);
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->where('Departamento', $departamento);
        }
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            
            $query->whereHas('clientes', function ($query) use ($cedula){
                $query->whereHas('cliente', function ($query) use ($cedula){
                    $query->where('Clientes.Identificacion', $cedula);
                });
            });
        }
    }

    public function scopeEstado($query,$estado){
        if (!empty($estado)) {
            $query->where('estado', [$estado]);
        }
    }

    public function scopeTipo($query,$tipo){
        if (!empty($tipo)) {
            $query->where('Tipo', [$tipo]);
        }
    }
}
