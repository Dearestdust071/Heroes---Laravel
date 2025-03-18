<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Producto;

class ProductoController extends Controller
{
  public function getProductos(Request $request)
  {

    $parameters = $request->parameters;
    $sort = isset($parameters['sortOrder'])  && $parameters['sortOrder'] == 1 ? 'asc' : 'desc';
    $sortfield = isset($parameters['sortField']) ?  $parameters['sortField'] : 'nombre';
    $condicion = [];
    if (!empty($parameters['globalFilter'])) {
      $filtro = '%' . $parameters['globalFilter'] . '%';
      $condicion = function ($query) use ($filtro) {
        $query->where('nombre', 'like', $filtro)
          ->orWhere('genero', 'like', $filtro)
          ->orWhere('categoria', 'like', $filtro);
      };
    }
    $producto = Producto::where($condicion);
    $productos = $producto
      ->orderBy($sortfield, $sort)
      ->offset($parameters['first'])
      ->limit($parameters['rows'])
      ->get()
      ->toArray();

    $total = $producto->count();
    return response()->json(['data' => $productos, 'totalRecords' => $total]);
  }


  public function getCategorias()
  {
    $producto = Producto::select(["categoria"])->groupBy("categoria")->get()->toArray();
    return response()->json($producto);
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
    $data = $request->only(['nombre', 'genero', 'descripcion', 'url', 'categoria']);

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
