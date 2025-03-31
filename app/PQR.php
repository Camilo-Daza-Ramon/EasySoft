<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PQR extends Model
{
    protected $table = 'ClientesPQR';
    protected $primaryKey = 'PqrId';
    public $timestamps = false;
    protected $fillable = [
        'Prioridad',
        'CUN',
        'TipoEntrada',
        'TipoSolicitud',
        'TipoDeEvento',

        'Hechos',
        'Solucion',
        'Observacion',
        'TipoTicket',
        'MunicipioId',

        'ClienteId',
        'ProyectoId',

        'NombreBeneficiario',
        'IdentificacionCliente',
        'CorreoElectronico',
        'DireccionNotificacion',
        'NumeroDeTelefono',
        'NumeroDeCelular',

        'AvisoDePrivacidad',
        'AutorizaTratamientoDatos',

        'FechaApertura',
        'FechaEstimada',
        'FechaMaxima',
        'FechaCierre',
        'TiempoTotal',
        'MarcaTiempo',    

        'user_crea',//'UsuarioIdAtendio',
        'user_cerro',//'UsuarioIdRespondio',

        'SoporteId',
        'Status'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function evento(){
        return $this->belongsTo(Evento::class, 'TipoDeEvento');
    }

    public function medio_atencion(){
        return $this->belongsTo(TicketMedioAtencion::class, 'TipoEntrada');
    }

    public function archivos(){
        return $this->hasMany(PqrArchivo::class, 'PqrId');
    }

     public function proyectos(){
        return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'MunicipioId', 'MunicipioId');
    }

    public function paradas_reloj(){
        return $this->hasMany(ParadaReloj::class, 'Pqrid');
    }

    public function tipo_pqr(){
        return $this->belongsTo(TipoPqr::class, 'TipoTicket');
    }

    public function usuario_crea(){
        return $this->belongsTo(User::class, 'user_crea');
    }

    public function usuario_cierra(){
        return $this->belongsTo(User::class, 'user_cerro');
    }


    public function scopeCUN($query,$cun){
        if (!empty($cun)) {
            $query->where('CUN', $cun);
            
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
            $query->where('IdentificacionCliente', $cedula);
        }
    }

    public function scopeEstado($query,$estado){
        if (!empty($estado)) {
            $query->where('Status', [$estado]);
        }
    }


}
