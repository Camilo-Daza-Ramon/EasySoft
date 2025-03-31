<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ClientesTickets';
    protected $primaryKey = 'TicketId';
    protected $fallable = [
        'TipoDeEntrada',
        'FechaApertura',
        'EstadoDeTicket',
        'ProyectoId',
        'UbicacionId',
        'ClienteId',
        'CodigoTipoDeFallo',
        'CodigoProblema',
        'DescripcionDelProblema',
        'Clasificacion',
        'Diagnostico',
        'TipoDeTicket',
        'IdentificadorTicket',
        'FechaSolucionEstimada',
        'FechaCierre',
        'FechaDeSolucion',
        'Solucion',
        'Observacion',
        'NombreDelCliente',
        'Identificacion',
        'Nota1',
        'Nota2',
        'Nota3',
        'UserId',
        'Escalado',
        'Correo',
        'CorreoCliente',
        'PrioridadTicket',
        'MarcaTiempo',
        'EstadoDelServicio',
        'TicketTemporalId',
        'ResponsableFallo',
        'Numero',
        'FechaEscalado',
        'HoraApertura',
        'HoraCierre',
        'HoraSolucion',
        'ReEscaladoA',
        'FechaMantenimiento',
        'CuadrillaAsignada',
        'CUN',
        'CodigoCierreId',
        'SeAfectoServicio',
        'user_crea'
    ];

    public $timestamps = false;

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

    public function estado(){
    	return $this->belongsTo(EstadoTicket::class, 'EstadoDeTicket');
    }

    public function tipo_fallo(){
        return $this->belongsTo(TipoFallo::class, 'CodigoTipoDeFallo');
    }

    public function novedad(){
        return $this->hasMany(Novedad::class, 'ticket_id');
    }

    public function medio_atencion(){
        return $this->belongsTo(TicketMedioAtencion::class, 'TipoDeEntrada');
    }

    public function prueba(){
        return $this->hasMany(TicketPrueba::class, 'TicketId');
    }

    public function mantenimiento(){
        return $this->hasOne(Mantenimiento::class,'TicketId');
    }

    public function agente_creo(){
        return $this->belongsTo(User::class, 'user_crea');
    }



    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->whereHas('cliente', function ($query) use ($cedula){
                $query->where('Clientes.Identificacion', $cedula);
            });
        }
    }

    public function scopeTicket($query,$ticket){
        if (!empty($ticket)) {
            $query->where('TicketId', [$ticket]);
        }
    }

    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->whereHas('cliente', function ($query) use ($proyecto){
                $query->where('Clientes.ProyectoId', $proyecto);
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

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
                $query->whereHas('municipio', function ($query) use ($departamento){
                    $query->where('Municipios.DeptId', $departamento);
                });
            });
        }
    }

    public function scopeEstado($query,$estado){
        if (count($estado) > 0) {
            $query->where('EstadoDeTicket', [$estado]);
        }
    }


}
