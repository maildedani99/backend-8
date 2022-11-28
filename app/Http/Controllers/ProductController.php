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
    { {
            return response()->json(Product::with('images')->get());
        }
    }

    public function getById($id)
    {
        Log::info('Retrieving product with id: ' . $id);
        $data = Product::findOrFail($id);
        $data['images'] = Image::where('product_id', $id)->get();
        return response()->json($data);
    }



    public function getByCategory($category_id)
    {
        Log::info('Retrieving product with category: ' . $category_id);
        $data = Product::with('images')->where('category_id', $category_id)->get();
        return response()->json($data);
    }

    public function getBySubCategory($subcategory_id)
    {
        Log::info('Retrieving product with category: ' . $subcategory_id);
        $data = Product::with('images')->where('subcategory_id', $subcategory_id)->get();
        return response()->json($data);
    }

    public function delete($id)
    {
        Product::where('id', $id)->delete();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = Product::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'category_id' => $request->get('category_id'),
            'subcategory_id' => $request->get('subcategory_id'),
        ]);
        if ($request->novelty === 1) {
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
