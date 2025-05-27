<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApCajas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ApCajas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noeconomico');
            $table->string('placas');
            $table->date('inspeccionmecanica');
            $table->string('ultimaubicacion');            
            $table->string('usuario');
            $table->date('fechamodifica');
            $table->integer('activo');
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
        //
    }
}
