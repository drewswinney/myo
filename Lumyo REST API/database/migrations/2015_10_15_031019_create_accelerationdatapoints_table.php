<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccelerationdatapointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accelerationdatapoints', function (Blueprint $table) {
            $table->increments('id');
            $table->float('adpXAcceleration');
            $table->float('adpYAcceleration');
            $table->float('adpZAcceleration');
            $table->dateTime('adpDateTime');
            $table->bigInteger('sessionID');
            $table->bigInteger('adpDeleted');
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
        Schema::drop('accelerationdatapoints');
    }
}
