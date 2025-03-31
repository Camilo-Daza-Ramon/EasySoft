<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitudDocumento extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    
    public $correo_origen;
    public $nombre_remitente;
    public $nombre_documento;
    public $observaciones;
    public $licitacion;
    public function __construct($correo_origen, $nombre_remitente,$nombre_documento,$observaciones, $licitacion)
    {
        //
        $this->correo_origen = $correo_origen;
        $this->nombre_remitente = $nombre_remitente;
        $this->nombre_documento = $nombre_documento;
        $this->observaciones = $observaciones;
        $this->licitacion = $licitacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.solicitud_documentos')->from($this->correo_origen, 'SISTECO - Intranet')->subject('Solicitud de Documentación Licitacion #' . $this->licitacion); 
    }
}
