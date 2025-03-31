<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Bienvenida extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $correo_destino;
    public $nombre_remitente;
    public $usuario;
    public $contrasena;
    public function __construct($correo_destino, $nombre_remitente, $usuario, $contrasena)
    {
        $this->correo_destino = $correo_destino;
        $this->nombre_remitente = $nombre_remitente;
        $this->usuario = $usuario;
        $this->contrasena = $contrasena;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        return $this->markdown('emails.bienvenida')->from('notificaciones@sisteco.com.co', 'SISTECO S.A.S')->subject('Bienveni@ a CitasSoft'); 
    }
}
