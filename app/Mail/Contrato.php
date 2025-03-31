<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;

class Contrato extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data,$archivos;
    
    public function __construct($datos, $files)
    {
        $this->data = $datos;
        $this->archivos = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');

        $enviar = $this->markdown('emails.contrato')->from('notificaciones@sisteco.co', 'Amigo Red')->subject('Su contrato de Amigo Red');

        foreach ($this->archivos as $archivo) {
            $enviar->attach(Storage::disk('public')->path($archivo));
        }

        return $enviar;           
    }
}
