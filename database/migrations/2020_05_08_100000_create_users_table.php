<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('user_identifier')->unique();
            $table->string('name', 250)->nullable();
            $table->string('firstname', 250)->nullable();
            $table->string('email')->unique();
            $table->string('username', 200)->unique()->nullable();
            $table->string('avatar')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('rank_id')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('ip')->default('127.0.0.1')->nullable();
            $table->string('token')->nullable();
            $table->string('token_reset')->nullable();
            $table->string('token_account')->nullable();
            $table->timestamps();
            $table->foreign('rank_id')->references('id')->on('ranks');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
