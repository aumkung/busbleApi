<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('gender')->nullable(true);
            $table->string('thumbnail')->nullable(true);
            $table->string('telno')->nullable(true)->unique();
            $table->string('email')->nullable(true)->unique();
            $table->string('telno_verify')->defaule(false)->nullable(true);
            $table->string('email_verify')->defaule(false)->nullable(true);
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
        Schema::dropIfExists('users');
    }
}
