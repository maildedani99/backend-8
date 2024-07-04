<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function all()
    {
        $orders = Order::with('items')->get();
        return response()->json($orders);
    }

    public function create(Request $request)
    {
       
        // Crear la orden
        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->total = $request->orderAmount;
        $order->status = 'pending';
        Log::info('order total', ['total'=>$order->total]);

        try {
            $order->save();

            // Agregar los ítems a la orden
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'size_id' => $item['size_id'],
                    'color_id' => $item['color_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return response()->json($order->load('items'), 201); // Devuelve la orden con los ítems cargados
        } catch (\Exception $e) {
            Log::error('Order processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Order processing failed', 'details' => $e->getMessage()], 500);
        }
    }
}
