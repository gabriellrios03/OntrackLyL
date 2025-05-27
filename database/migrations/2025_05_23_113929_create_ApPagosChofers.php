<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApPagosChofers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ApPagosChofers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('viajeID');
            $table->string('ExtrasID');
            $table->integer('choferID');
            $table->decimal('monto', 10,2);
            $table->date('fecha');
            $table->string('usuario');
            $table->date('fechamodifica');
            $table->integer('activo');
            $table->string('estado');
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
        Schema::dropIfExists('ap_pagos_chofers');
    }
}
