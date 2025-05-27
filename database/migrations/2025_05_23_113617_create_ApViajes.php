<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApViajes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ApViajes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Id_ruta');
            $table->date('fecha');
            $table->integer('Id_chofer');
            $table->integer('Id_camion');
            $table->string('estado');
            $table->integer('cajainicial');
            $table->integer('cajafinal');
            $table->string('selloinicial');
            $table->string('sellofinal');
            $table->integer('evidencia');
            $table->integer('facturado');
            $table->integer('finalizado');            
            $table->string('usuario');
            $table->date('fechamodifica');
            $table->integer('activo');
            $table->string('motivo');
            $table->integer('ClienteID');
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
        Schema::dropIfExists('ap_viajes');
    }
}
