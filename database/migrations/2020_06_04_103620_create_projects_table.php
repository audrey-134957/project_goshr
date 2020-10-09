<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title', 90)->unique();
            $table->string('slug', 150)->unique();
            $table->unsignedMediumInteger('id_number');
            $table->string('thumbnail', 3000)->nullable();
            $table->unsignedTinyInteger('duration')->nullable();
            $table->unsignedBigInteger('unity_of_measurement_id');
            $table->unsignedBigInteger('difficulty_level_id');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->unsignedTinyInteger('budget')->nullable();
            $table->longText('content');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('unity_of_measurement_id')->references('id')->on('unities_of_measurement');
            $table->foreign('difficulty_level_id')->references('id')->on('difficulty_levels');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->boolean('fictionnal_deletion')->default(0);
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
        Schema::dropIfExists('projects');
    }
}
