<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{



  public function getProductos(Request $request)
  {
    // Obtener parámetros de ordenación
    $sort = $request->input('sortOrder', 1) == 1 ? 'asc' : 'desc';
    $sortfield = $request->input('sortField', 'nombre');
    $limit = $request->input('rows', 10);
    $offset = $request->input('first', 0);

    // Obtener las categorías del parámetro 'catalogo' como un arreglo de IDs
    $catalogo = $request->input('parameters.catalogo', []);

    // Inicializar la consulta de productos
    $producto = Producto::with('categoria'); // <- Aquí incluimos la relación

    // Aplicar filtro global (si es necesario)
    if (!empty($request->input('globalFilter'))) {
      $filtro = '%' . $request->input('globalFilter') . '%';
      $producto->where(function ($query) use ($filtro) {
        $query->where('nombre', 'like', $filtro)
          ->orWhere('genero', 'like', $filtro)
          ->orWhere('descripcion', 'like', $filtro);
      });
    }

    // Aplicar filtro por categorías (solo si se pasaron categorías)
    if (!empty($catalogo)) {
      $producto->whereIn('categoria_id', $catalogo);
    }

    // Obtener el total de productos
    $total = $producto->count();

    // Obtener los productos con paginación y ordenación
    $productos = $producto
      ->orderBy($sortfield, $sort)
      ->offset($offset)
      ->limit($limit)
      ->get()
      ->map(function ($producto) {
        return [
          'id' => $producto->id,
          'nombre' => $producto->nombre,
          'genero' => $producto->genero,
          'descripcion' => $producto->descripcion,
          'url' => $producto->url,
          'categoria' => $producto->categoria ? $producto->categoria->nombre : null, // <- Aquí convertimos el ID en el nombre
          'created_at' => $producto->created_at,
          'updated_at' => $producto->updated_at
        ];
      });

    // Retornar los productos junto con el total de registros
    return response()->json(['data' => $productos, 'totalRecords' => $total]);
  }







  public function getCategorias()
  {
    $producto = Producto::distinct()->pluck("categoria")->toArray(); // Optimizado
    return response()->json($producto);
  }



  public function categoria()
  {
    return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
  }



  public function getProductoById($productoId)
  {
    $producto = Producto::find($productoId);

    $array = $producto ? [
      'id' => (int)$producto->id,
      'nombre' => $producto->nombre,
      'url' => $producto->url,
      'categoria' => $producto->categoria,
      'genero' => $producto->genero,
      'descripcion' => $producto->descripcion,
      'created_at' => $producto->fecha
    ] : [];

    return response()->json($array);
  }

  public function insertProducto(Request $request)
  {
    $data = $request->only(['nombre', 'genero', 'descripcion', 'url', 'categoria_id']);

    $producto = Producto::create($data);

    return response()->json(['estatus' => true, 'id' => $producto->id]);
  }

  public function updateProducto(Request $request, $producto_id)
  {
    $data = $request->only(['nombre', 'genero', 'descripcion', 'url', 'categoria']);

    $producto = Producto::find($producto_id);

    if (!$producto) {
      return response()->json(['estatus' => false]);
    }

    $producto->update($data);

    return response()->json(['estatus' => true]);
  }

  public function deleteProducto($producto_id)
  {
    $producto = Producto::find($producto_id);

    if (!$producto) {
      return response()->json(['estatus' => false]);
    }

    $producto->update(['eliminado' => 0]);

    return response()->json(['estatus' => true]);
  }
}
