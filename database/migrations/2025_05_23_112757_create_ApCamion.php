<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApCamion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ApCamion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noeconomico');
            $table->string('placas');
            $table->string('nomotor');
            $table->date('fechaverifica');
            $table->decimal('ultimokilom', 10, 2);
            $table->string('verifhumos');                        
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
        Schema::dropIfExists('ap_camions');
    }
}
