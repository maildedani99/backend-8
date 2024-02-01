<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSizeColor;


class ProductSizeColorController extends Controller
{
    public function all()
    {
        $productSizeColors = ProductSizeColor::all();
        return response()->json($productSizeColors);
    }

    public function create(Request $request)
    {
        $productSizeColor = ProductSizeColor::create([
            'quantity' => $request->get('quantity'),
            'product_id' => $request->get('product_id'),
            'size_id' => $request->get('size_id'),
            'color_id' => $request->get('color_id'),
        ]);

        return response()->json($productSizeColor);
    }
}
