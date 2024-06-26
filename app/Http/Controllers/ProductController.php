<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\Novelty;
use App\Models\Outlet;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $products = Product::with('images')->get();
        return response()->json($products)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Display a listing of all products with stock.
     *
     * @return \Illuminate\Http\Response
     */
    public function allStock()
    {
        $products = Product::with('images')->has('stock')->get();
        return response()->json($products)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Get a single product by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $product = Product::with(['images', 'stock'])->where('id', $id)->first();
        return response()->json($product)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Display all outlet products.
     *
     * @return \Illuminate\Http\Response
     */
    public function outlet()
    {
        $outlets = Product::with(['outlet', 'images', 'stock'])->has('stock')->has('outlet')->get();
        return response()->json($outlets)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Display all novelty products.
     *
     * @return \Illuminate\Http\Response
     */
    public function novelties()
    {
        $novelties = Product::with(['images', 'stock'])->has('stock')->has('novelties')->get();
        return response()->json($novelties)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Display all discounted products.
     *
     * @return \Illuminate\Http\Response
     */
    public function discounts()
    {
        $discounts = Product::with('images')->has('stock')->where(function ($query) {
            $query->where('reduced_price', '>', 0)->orWhereNull('reduced_price');
        })->get();
        return response()->json($discounts)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Get products by subcategory ID.
     *
     * @param  int  $subcategory_id
     * @return \Illuminate\Http\Response
     */
    public function getBySubCategory($subcategory_id)
    {
        $products = Product::with('images')->where('subcategory_id', $subcategory_id)->has('stock')->get();
        return response()->json($products)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Create a new product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $product = Product::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
                'subcategory_id' => $request->get('subcategory_id'),
                'reduced_price' => $request->get('reduced_price'),
            ]);

            if ($request->has('novelty') && $request->get('novelty')) {
                Novelty::create([
                    'product_id' => $product->id
                ]);
            }
            if ($request->has('outlet') && $request->get('outlet')) {
                Outlet::create([
                    'product_id' => $product->id
                ]);
            }

            foreach ($request->images as $image) {
                $image = Image::create([
                    'url' => $image,
                    'product_id' => $product->id
                ]);
                $product->images()->save($image);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $id = $request->get('product_id');
            $product = Product::findOrFail($id);

            $product->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
                'subcategory_id' => $request->get('subcategory_id'),
                'reduced_price' => $request->get('reduced_price'),
            ]);

            // Handling novelty and outlet flags
            if ($request->get('novelty')) {
                Novelty::updateOrCreate(['product_id' => $id]);
            } else {
                Novelty::where('product_id', $id)->delete();
            }

            if ($request->get('outlet')) {
                Outlet::updateOrCreate(['product_id' => $id]);
            } else {
                Outlet::where('product_id', $id)->delete();
            }

            // Update images if new ones are provided
            if ($request->has('images')) {
                $product->images()->delete();
                foreach ($request->get('images') as $image) {
                    Image::create([
                        'url' => $image,
                        'product_id' => $product->id
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Product::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully'], 200);
    }
}
