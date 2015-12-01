<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatapointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datapoints', function (Blueprint $table) {
            $table->increments('id');
            $table->float('dpXRotation');
            $table->float('dpYRotation');
            $table->float('dpZRotation');
            $table->bigInteger('dpPod1');
            $table->bigInteger('dpPod2');
            $table->bigInteger('dpPod3');
            $table->bigInteger('dpPod4');
            $table->bigInteger('dpPod5');
            $table->bigInteger('dpPod6');
            $table->bigInteger('dpPod7');
            $table->bigInteger('dpPod8');
            $table->bigInteger('sessionID');
            $table->bigInteger('dpDeleted');
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
        Schema::drop('datapoints');
    }
}
