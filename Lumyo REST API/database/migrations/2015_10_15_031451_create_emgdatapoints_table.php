<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmgdatapointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emgdatapoints', function (Blueprint $table) {
            $table->increments('id');
            $table->float('emgpPod1');
            $table->float('emgpPod2');
            $table->float('emgpPod3');
            $table->float('emgpPod4');
            $table->float('emgpPod5');
            $table->float('emgpPod6');
            $table->float('emgpPod7');
            $table->float('emgpPod8');
            $table->dateTime('emgpDateTime');
            $table->bigInteger('sessionID');
            $table->bigInteger('emgpDeleted');
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
        Schema::drop('emgdatapoints');
    }
}
