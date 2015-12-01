<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardGraphTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboardgraph', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dgSize');
            $table->string('dgRow');
            $table->bigInteger('dgtID');
            $table->bigInteger('loginID');
            $table->bigInteger('dgDeleted');
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
        Schema::drop('dashboardgraph');
    }
}
