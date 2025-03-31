<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'Mantenimientos';
    protected $primaryKey = 'MantId';
    protected $fallable = [
        
        'Barrrio',
        'ClienteId',
        'CorreoCliente',
        'Cuadrilla',
        
        'DescripcionProblema',
        'Direccion',
        'estado',

        'Fecha', //creación del mantenimiento ya sea correctivo cliente o correctivo masivo
        'FechaMaxima',

        'fecha_cierre_hora_inicio', 
        'fecha_cierre_hora_fin',

        'Latitud',
        'Longitud',
        'DepartamentoId',
        'MunicipioId',
        'NumeroDeTicket',
        'ObservacionDeCierre',
        'Observaciones',
        'PararReloj',
        'Prioridad',
        'Procedimiento',
        'ProyectoId',
        'Red',
        'SeRetornoServicio',
        'Serial',
        'SerialDeRemplazo',
        'ServicioQuedaActivo',
        'Solucion',
        'TicketId',
        'TipoCierreId',
        'TipoDeTecnologiaImplementada',
        'TipoEntrada',
        'TipoFalloID',
        'TipoMantenimiento',
        
        'VelocidadDeBajada',
        'VelocidadDeSubida',

        'AgenteCreaMantenimiento',
        
        'user_crea',
        'user_atiende',
        'user_cerro',

        'parentezco',
        'nombre',
        'cedula',
        'firma',
    ];
    
    public $timestamps = false;

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class, 'TicketId');
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

    public function tipo_fallo(){
        return $this->belongsTo(TipoFallo::class, 'TipoFalloID', 'TipoFallaId');
    }

    public function medio_atencion(){
        return $this->belongsTo(TicketMedioAtencion::class, 'TipoEntrada', 'TipoEntradaTicket');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'MunicipioId', 'MunicipioId');
    }

    public function tipo_mantenimiento(){
        return $this->belongsTo(TipoMantenimiento::class, 'TipoMantenimiento');
    }

    public function clientes(){
        return $this->hasMany(MantenimientoCliente::class, 'MantId');
    }

    public function archivos(){
        return $this->hasMany(MantenimientoArchivo::class, 'mantenimiento_id');
    }

    public function paradas_reloj(){
        return $this->hasMany(MantenimientoParadaReloj::class,'Mantid');
    }

    public function equipos(){
        return $this->hasMany(MantenimientoEquipo::class,'MantId');
    }

    public function diagnosticos(){
        return $this->hasMany(MantenimientoDiagnostico::class,'MantId');
    }

    public function direcciones(){
        return $this->hasMany(MantenimientoDireccion::class,'MantId');
    }

    public function pruebas(){
        return $this->hasMany(MantenimientoPrueba::class, 'mantenimiento_id');
    }

    public function soluciones(){
        return $this->hasMany(MantenimientoSolucion::class, 'mantenimiento_id');
    }

    public function fallas(){
        return $this->hasMany(MantenimientoFalla::class, 'MantId');
    }

    public function materiales(){
        return $this->hasMany(MantenimientoMaterial::class, 'MantId');
    }


    public function scopeBuscar($query,$mantenimiento){
        if (!empty($mantenimiento)) {

            if (is_numeric($mantenimiento)) {
                $query->where('MantId', $mantenimiento)
                ->orWhere(function($query) use($mantenimiento){
                    $query->whereHas('cliente', function ($query) use ($mantenimiento){
                        $query->where('Clientes.Identificacion', $mantenimiento);
                    });
                });            
            }else{
                $query->where('NumeroDeTicket', $mantenimiento);
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
            $query->where('MunicipioId', $municipio);
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('municipio', function ($query) use ($departamento){
                $query->where('Municipios.DeptId', $departamento);
            });
        }
    }

    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            
                $query->whereHas('cliente', function ($query) use ($cedula){
                    $query->where('Clientes.Identificacion', $cedula);
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
            $query->where('TipoMantenimiento', [$tipo]);
        }
    }

}
