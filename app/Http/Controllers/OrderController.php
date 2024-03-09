<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{



    public function all()
{
    $orders = Order::with('items')->get();
    return response()->json($orders);
}

    public function create(Request $request)
{
    $order = new Order();
    $order->customer_id = $request->customer_id; // Asegúrate de enviar el ID del cliente en la solicitud
    $order->total = $request->total; // Asume que 'total' es enviado en la solicitud
    $order->status = 'pending'; // Marcamos inicialmente el pedido como pendiente
    $order->ds_order = $request->ds_order; // El identificador del pedido en el sistema de Redsys
    $order->save();

    return response()->json($order, 201);
}
}
