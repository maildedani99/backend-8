<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return response()->json(Subcategory::all());
    }

    public function getById($id)
    {
        return response()->json(Subcategory::where('id', $id)->get());
    }


    public function create(Request $request)
    {
        $subcategory = Subcategory::create([
            'name' => $request->get('name'),
            'category_id' => $request->get('category_id')
        ]);
        return $subcategory;
    }

    public function delete($id)
    {
        Subcategory::where('id', $id)->delete();
    }

    function store(Request $request)
    {
        //
    }

    public function show(Subcategory $subcategory)
    {
        //
    }


    public function edit(Subcategory $subcategory)
    {
        //
    }


    public function update(Request $request, Subcategory $subcategory)
    {
        //
    }
}
