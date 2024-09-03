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
        $quantity = $request->get('quantity');

        // Buscar si ya existe un registro con los mismos IDs
        $existingStock = Stock::where('product_id', $product_id)
                            ->where('size_id', $size_id)
                            ->where('color_id', $color_id)
                            ->first();

        if ($existingStock) {
            // Si la cantidad es 0, eliminar el registro
            if ($quantity == 0) {
                $existingStock->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Stock deleted successfully'
                ], 200);
            }

            // Si existe y la cantidad no es 0, actualizar la cantidad
            $existingStock->quantity = $quantity;
            $existingStock->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Stock updated successfully',
                'data' => $existingStock
            ], 200);
        }

        // Si no existe y la cantidad es mayor que 0, crear un nuevo registro
        if ($quantity > 0) {
            $stock = Stock::create([
                'quantity' => $quantity,
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

        // Si no existe y la cantidad es 0, no hacer nada
        return response()->json([
            'status' => 'error',
            'message' => 'Quantity cannot be 0 when creating new stock'
        ], 400);
    }

    public function delete($id)
    {
        Stock::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Stock deleted successfully'
        ], 200);
    }
}
