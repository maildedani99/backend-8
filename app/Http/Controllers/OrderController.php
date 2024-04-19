<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->total = $request->orderAmount;
        $order->status = 'pending';
        Log::info('order total', ['total'=>$order->total]);

        try {
            $order->save();
            return response()->json($order, 201);
        } catch (\Exception $e) {
            Log::error('Order processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Order processing failed', 'details' => $e->getMessage()], 500);
        }
    }
}
