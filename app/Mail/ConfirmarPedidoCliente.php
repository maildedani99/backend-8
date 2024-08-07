<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmarPedidoCliente extends Mailable
{
    use Queueable, SerializesModels;

    public $contenido;
    public $emailCliente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contenido, $emailCliente)
    {
        $this->contenido = $contenido;
        $this->emailCliente = $emailCliente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@usuriaga.com')
                    ->to($this->emailCliente)
                    ->subject('Confirmación de tu Pedido')
                    ->html($this->contenido);
    }
}

