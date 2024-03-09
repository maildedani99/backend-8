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
        DB::beginTransaction();

        try {
            $customerData = $request->input('customer');
            $orderData = $request->input('order');
            $items = $request->input('items');

            $customer = Customer::create($customerData);

            $orderData['customer_id'] = $customer->id;

            $order = Order::create($orderData);

            foreach ($items as $item) {
                $itemData = array_merge($item, ['order_id' => $order->id]);
                OrderItem::create($itemData);
            }

            DB::commit();

            return response()->json(['message' => 'Order processed successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Order processing failed', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Order processing failed', 'details' => $e->getMessage()], 500);
        }
    }
}
