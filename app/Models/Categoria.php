<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
  use HasFactory;

  // Si el nombre de la tabla no es el plural del modelo, lo especificas aquí
  protected $table = 'categorias';

  // Si el campo 'id' no es auto incrementable, indicamos que no es clave primaria autoincremental
  protected $primaryKey = 'id';

  // Indicar que 'id' no es un entero autoincremental
  public $incrementing = false;

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'id',
    'nombre',
    'estatus'
  ];
}
