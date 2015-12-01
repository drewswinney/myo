<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardappletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboardapplet', function (Blueprint $table) {
            $table->increments('daID');
            $table->bigInteger('graphID');
            $table->string('daSize');
            $table->string('daRow');
            $table->bigInteger('loginID');
            $table->bigInteger('daDeleted');
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
        Schema::drop('dashboardapplet');
    }
}
