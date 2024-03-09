<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function all()
    {
        //return response()->json(Category::all());
        return response()->json(Customer::all());
    }

    public function create(Request $request)
{

    $customer = Customer::create($request->all());
    return response()->json($customer, 201);
}
}

