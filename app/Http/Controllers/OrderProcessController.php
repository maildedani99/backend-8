<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class OrderProcessController extends Controller
{
    public function completeOrderProcess(Request $request)
    {
        Log::info('se inicia completeOrderProcess ');
        DB::beginTransaction();
        try {
            $customerData = $request->input('customer');
            $orderData = $request->input('order');
            Log::info('log for order', ['order'=>$orderData]);
            $items = $request->input('items');

            $customer = Customer::create($customerData);

            $orderData['ds_order'] = Order::generateDsOrder();
            $orderData['customer_id'] = $customer->id;
            $orderData['total'] = $orderData['orderAmount'];

            Log::info('orderData', ['orderData for reate'=>$orderData]);

            $order = Order::create($orderData);

            foreach ($items as $item) {
                $itemData = array_merge($item, ['order_id' => $order->id]);
                OrderItem::create($itemData);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order processed successfully',
                'order' => [
                    'ds_order' => $order->ds_order,
                    'total' => $order->total,
                    'customer_id' => $order->customer_id,
                    // Añade aquí cualquier otro dato que necesites
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Order processing failed', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Order processing failed', 'details' => $e->getMessage()], 500);
        }
    }
}
