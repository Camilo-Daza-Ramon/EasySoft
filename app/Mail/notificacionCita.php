<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class notificacionCita extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $paciente;
    public $especialidad;
    public $medico;
    public $fecha_cita;
    public $hora_cita;
    public $sede;
    public $direccion;
    public $mapa;


    public function __construct($paciente, $especialidad, $medico, $fecha_cita, $hora_cita, $sede, $direccion, $mapa)
    {
        //
        $this->paciente = $paciente;
        $this->especialidad = $especialidad;
        $this->medico = $medico;
        $this->fecha_cita = $fecha_cita;
        $this->hora_cita = $hora_cita;
        $this->sede = $sede;
        $this->direccion = $direccion;
        $this->mapa = $mapa;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('emails.notificacion_cita')->subject('Confirmación de Cita');
        //return $this->view('emails.notificacion_cita');
        //return $this->markdown
        return $this->markdown('emails.notificacion_cita')->from('notificaciones@sisteco.com.co','CitasSoft')->subject('Confirmación de Cita');
    }
}
