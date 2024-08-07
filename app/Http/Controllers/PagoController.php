<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmarPedidoCliente;
use App\Mail\NotificarPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PagoController extends Controller
{
    public function procesarNotificacion(Request $request)
    {
        // Obtener la información del pedido y el estado del pago desde el request
        $estadoPago = $request->input('estado_pago'); // 'confirmed' o 'pending'
        $pedido = // lógica para obtener el pedido basado en los datos de la notificación

        // Obtener el contenido de los correos desde el frontend o generar contenido basado en la lógica
        $contenidoParaVentas = "Detalles del pedido...";
        $contenidoParaCliente = "Detalles del pedido...";


            Mail::send(new ConfirmarPedidoCliente("Tu pedido está pendiente de confirmación. Te avisaremos cuando se confirme.", "maildedani9@gmail.com"));


        // Enviar correo al equipo de ventas
        Mail::send(new NotificarPedido($contenidoParaVentas));

        // Responder a la pasarela de pago o realizar otras acciones necesarias
        return response()->json(['status' => 'success']);
    }
}
