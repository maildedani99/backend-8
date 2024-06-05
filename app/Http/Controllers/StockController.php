<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function all()
    {
        $stock = Stock::all();
        return response()->json($stock);
    }

    public function create(Request $request)
    {
        $product_id = $request->get('product_id');
        $size_id = $request->get('size_id');
        $color_id = $request->get('color_id');

        // Buscar si ya existe un registro con los mismos IDs
        $existingStock = Stock::where('product_id', $product_id)
                            ->where('size_id', $size_id)
                            ->where('color_id', $color_id)
                            ->first();

        if ($existingStock) {
            // Si existe, actualizar la cantidad
            $existingStock->quantity += $request->get('quantity');
            $existingStock->save();


        return response()->json([
            'status' => 'success',
            'message' => 'Stock created successfully',
            'data' => $existingStock    
        ], 201);
        }

        // Si no existe, crear un nuevo registro
        $stock = Stock::create([
            'quantity' => $request->get('quantity'),
            'product_id' => $product_id,
            'size_id' => $size_id,
            'color_id' => $color_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Stock created successfully',
            'data' => $stock
        ], 201);
    }

    public function delete($id)
    {
        Stock::where('id', $id)->delete();

    }
}
