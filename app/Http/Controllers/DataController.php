<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Models\Novelty;
use App\Models\Outlet;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function getAllData()
    {
        try {
            $categories = Category::all();
            $sizes = Size::all();
            $colors = Color::all();
            $products = Product::with('images')->get()->all();
            $subcategories = Subcategory::all();
            $novelties = Novelty::all();
            $outlets = Outlet::all();

            return response()->json([
                'categories' => $categories,
                'sizes' => $sizes,
                'colors' => $colors,
                'products' => $products,
                'subcategories' => $subcategories,
                'novelties' => $novelties,
                'outlets' => $outlets

            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data'], 500);
        }
    }


    public function getSizesColors()
    {
        try {
            $sizes = Size::all();
            $colors = Color::all();

            return response()->json([
                'sizes' => $sizes,
                'colors' => $colors,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data'], 500);
        }
    }
}
