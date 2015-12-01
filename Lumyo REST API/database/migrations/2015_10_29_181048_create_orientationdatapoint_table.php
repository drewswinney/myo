<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrientationdatapointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orientationdatapoint', function (Blueprint $table) {
            $table->increments('id');
            $table->float('odpXRotation');
            $table->float('odpYRotation');
            $table->float('odpZRotation');
            $table->dateTime('odpDateTime');
            $table->bigInteger('sessionID');
            $table->bigInteger('odpDeleted');
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
        Schema::drop('orientationdatapoint');
    }
}