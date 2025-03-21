<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
  // Obtener todas las categorías
  public function index()
  {
    $categorias = Categoria::all()->toArray(); // No poner pluck porque angular lo lee mal:w
    return response()->json($categorias);
  }



  public function cambiarEstatus(Request $request, $id)
  {
    $request->validate([
      'estatus' => 'required|in:0,1', // Solo permite valores 0 o 1
    ]);

    $categoria = Categoria::find($id);

    if (!$categoria) {
      return response()->json(['message' => 'Categoría no encontrada'], 404);
    }

    $categoria->estatus = $request->input('estatus');

    $categoria->save();

    return response()->json([
      'message' => 'Estatus actualizado correctamente',
      'categoria' => $categoria
    ]);
  }
}
