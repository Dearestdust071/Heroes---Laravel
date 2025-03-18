<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// SI EN ALGUN PUTO MOMENTO TE TIRA ERROR QUE NO SE QUE LOGIN METHOD NO ENONTRADO O ALGUNA COSA ASI 
// EN REALIDAD SOLO EL TOKEN ESTA MAL NO SE POR QUE DICE ESO 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});
Route::post('user/login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::put('/password/{id}', [UserController::class, 'password'])->where('id', '[0-9]+');
    Route::put('/editar/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
    Route::get('/user-profile', [UserController::class, 'userprofile']);
    Route::get('/logout', [UserController::class, 'logout']);
  });

  Route::prefix('productos')->group(function () {
    Route::post('/insert', [ProductoController::class, 'insertProducto']);
    Route::post('/filtered', [ProductoController::class, 'getProductos']);
    Route::get('', [ProductoController::class, 'getCategorias']);
    Route::get('/{producto_id}', [ProductoController::class, 'getProductoById'])->where('producto_id', '[1-9]+');
    Route::put('/{producto_id}', [ProductoController::class, 'updateProducto'])->where('producto_id', '[0-9]+');
    Route::delete('/{producto_id}', [ProductoController::class, 'deleteProducto'])->where('producto_id', '[0-9]+');
  });
});
