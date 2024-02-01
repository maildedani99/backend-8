<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function all()
    {
        return response()->json(Email::all());
    }

    public function create(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:emails',
        ]);
        // Creación del correo electrónico si la validación es exitosa
        $email = Email::create([
            'email' => $request->input('email'),
        ]);

        return response()->json($email, 201);
    }

    public function delete($id)
    {
        Email::where('id', $id)->delete();
    }
}
