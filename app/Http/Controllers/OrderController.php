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
        Log::info('order total', ['total' => $order->total]);

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

    public function getById($id)
    {
        try {
            $order = Order::with(['items', 'customer'])->findOrFail($id);
            return response()->json($order);
        } catch (\Exception $e) {
            Log::error('Order retrieval failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Order not found', 'details' => $e->getMessage()], 404);
        }
    }

    public function getByOrder($order)
    {
        try {
            $order = Order::with(['items', 'customer'])->where('ds_order', $order)->firstOrFail();
            return response()->json($order);
        } catch (\Exception $e) {
            Log::error('Order retrieval failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Order not found', 'details' => $e->getMessage()], 404);
        }
    }

    public function confirmOrder($orderNumber)
    {
        try {
            $order = Order::where('ds_order', $orderNumber)->firstOrFail();
            $order->status = 'completed';
            $order->save();

            Log::info('Order confirmed successfully', ['order_number' => $orderNumber]);

            return response()->json(['message' => 'Order confirmed successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Order confirmation failed', ['error' => $e->getMessage(), 'order_number' => $orderNumber]);
            return response()->json(['error' => 'Order confirmation failed', 'details' => $e->getMessage()], 500);
        }
    }
}
