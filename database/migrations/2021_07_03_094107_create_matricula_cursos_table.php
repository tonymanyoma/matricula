<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatriculaCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matricula_cursos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->unsignedInteger('id_usuario')->nullable();
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->unsignedInteger('id_estado')->nullable();
            $table->foreign('id_estado')->references('id')->on('estados');
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
        Schema::dropIfExists('matricula_cursos');
    }
}
