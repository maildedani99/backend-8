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
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        try {
            // Crear el nuevo color
            $color = Color::create([
                'name' => $validatedData['name'],
                'color' => $validatedData['color'],
            ]);

            // Devolver una respuesta de éxito
            return response()->json([
                'success' => true,
                'message' => 'Color creado exitosamente.',
                'color' => $color,
            ], 201);

        } catch (\Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el color.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function delete($id)
    {
        Color::where('id', $id)->delete();
    }
}
