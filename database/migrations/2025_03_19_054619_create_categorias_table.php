<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
  /**
   * Ejecuta la migración.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categorias', function (Blueprint $table) {
      $table->string('id')->primary(); // El campo 'id' es de tipo varchar
      $table->string('nombre'); // El nombre de la categoría
      $table->boolean('estatus'); // El estatus de la categoría (true o false)
      $table->timestamps(); // Para registrar las fechas de creación y actualización
    });
  }

  /**
   * Revertir la migración.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('categorias');
  }
}
