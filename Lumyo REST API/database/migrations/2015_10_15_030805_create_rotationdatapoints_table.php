<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotationdatapointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rotationdatapoints', function (Blueprint $table) {
            $table->increments('id');
            $table->float('rdpXRotation');
            $table->float('rdpYRotation');
            $table->float('rdpZRotation');
            $table->dateTime('rdpDateTime');
            $table->bigInteger('sessionID');
            $table->bigInteger('rdpDeleted');
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
        Schema::drop('rotationdatapoints');
    }
}
