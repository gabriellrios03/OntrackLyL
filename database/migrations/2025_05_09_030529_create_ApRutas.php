<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApRutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ApRutas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('origen');
            $table->string('destino');
            $table->decimal('kilomestima', 10, 2);
            $table->decimal('consumodiesel', 10, 2);
            $table->decimal('tarifacliente', 10, 2);
            $table->decimal('pagochofer', 10, 2);
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
