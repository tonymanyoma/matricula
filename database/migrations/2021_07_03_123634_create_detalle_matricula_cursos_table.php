<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleMatriculaCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_matricula_cursos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_matricula')->nullable();
            $table->foreign('id_matricula')->references('id')->on('matricula_cursos');
            $table->unsignedInteger('id_curso')->nullable();
            $table->foreign('id_curso')->references('id')->on('cursos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_matricula_cursos');
    }
}
