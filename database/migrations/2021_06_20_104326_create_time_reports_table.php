<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('userId')->index();
            $table->timestamp('starttime')->nullable();
            $table->timestamp('breakstarttime')->nullable();
            $table->timestamp('breakendtime')->nullable();
            $table->timestamp('endtime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_reports');
    }
}
