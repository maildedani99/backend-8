<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\Novelty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return response()->json(Product::with('images')
        ->get()
        ->all());
    }

    public function allStock()
    {
        return response()->json(Product::with('images')
        ->has('stock')
        ->get()
        ->all());
    }

    public function getById($id)
    {
        $data = Product::with('images', 'stock')->where('id', $id)->get();
        return response()->json($data);
    }


    public function outlet()
    {
        $data = Product::with('images', 'sizes', 'stock')->where('outlet', true)->get();
        return response()->json($data);
    }

    public function discounts()
    {
        $data = Product::with('images', 'sizes', 'stock')->where('discount', true)->get();
        return response()->json($data);
    }




    /*  public function getByCategory($category_id)
    {
        Log::info('Retrieving product with category: ' . $category_id);
        $data = Product::with('images')->where('category_id', $category_id)->get();
        return response()->json($data);
    } */

    public function getBySubCategory($subcategory_id)
    {
        Log::info('Retrieving product with category: ' . $subcategory_id);
        $data = Product::with('images')
        ->where('subcategory_id', $subcategory_id)
        ->has('stock')
        ->get();
        return response()->json($data);
    }

    public function novelties()
    {
        $novelties = Product::with(['novelties', 'images', 'stock'])
        ->where('outlet', false)
        ->where('discount', false)
        ->has('stock')  // Filtra solo los productos que tienen al menos un registro en la tabla stock
        ->get();
        return response()->json($novelties);
    }

    public function delete($id)
    {
        Product::where('id', $id)->delete();
    }


    public function create(Request $request)
    {
        $product = Product::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'subcategory_id' => $request->get('subcategory_id'),
            'outlet' => $request->get('outlet'),
            'discount' => $request->get('discount'),
            'reduced_price' => $request->get('reduced_price'),

        ]);
        if ($request->novelty === true) {
            Novelty::create([
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

        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
