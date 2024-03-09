<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderItemController extends Controller
{


    public function all () {
        return response()->json(OrderItem::all());
    }


    public function addItemsToOrder(Request $request)
{
    $items = $request->input('items');

    if (!is_array($items)) {
        return response()->json(['error' => 'Invalid input'], 400);
    }

    foreach ($items as $item) {
        OrderItem::create([
            'order_id' => $item['order_id'],
            'product_id' => $item['product_id'],
            'size_id' => $item['size_id'],
            'color_id' => $item['color_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }
    return response()->json(['message' => 'Items added successfully'], 201);
}

public function getItemsByOrderId($orderId)
{
    $items = OrderItem::where('order_id', $orderId)->get();

    if ($items->isEmpty()) {
        return response()->json(['error' => 'No items found for the provided order ID'], 404);
    }

    return response()->json($items);
}

}
