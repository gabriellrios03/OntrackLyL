<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApDiesel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ApDiesel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Id_camion');
            $table->decimal('km_inicial');
            $table->decimal('km_final');
            $table->string('sitio_recarga');            
            $table->decimal('costo_litro', 10,2);
            $table->decimal('total_litros', 10,2);
            $table->decimal('rendimiento', 10,2);
            $table->decimal('total_costo', 10,2);
            $table->date('fecha');
            $table->string('usuario');
            $table->date('fechamodifica');
            $table->integer('activo');
            $table->string('bandera');
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
