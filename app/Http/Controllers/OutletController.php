<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function all()
    {
        return response()->json(Outlet::all());
    }

    public function create(Request $request)
    {
        $outlet = Outlet::create([
            'product_id' => $request->get('product_id')
        ]);
        return $outlet;
    }

    public function delete(Outlet $outlet)
    {
        $outlet->delete();

        return response()->json(null, 204);
    }
}
