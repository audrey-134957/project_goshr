<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotiveReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motive_report', function (Blueprint $table) {
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('motive_id');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('motive_id')->references('id')->on('motives');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motive_report');
    }
}
