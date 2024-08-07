<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarPedido extends Mailable
{
    use Queueable, SerializesModels;

    public $contenido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contenido)
    {
        $this->contenido = $contenido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@usuriaga.com')
                    ->to('info@usuriaga.com')
                    ->subject('Nuevo Pedido Recibido')
                    ->html($this->contenido);
    }
}
