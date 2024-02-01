<?php

namespace App\Http\Controllers;


use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{

    public function all()
    {
        return response()->json(Color::all());
    }

    public function create(Request $request)
    {
        $color = Color::create([
            'name' => $request->get('name'),
            'color' => $request->get('color'),

        ]);
        return $color;
    }

    public function delete($id)
    {
        Color::where('id', $id)->delete();
    }
}
