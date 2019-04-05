<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->increments('id');

            $table->text('key');

            $table->integer('privilege')->unsigned();

            $table->integer('teacher_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();

            $table->dateTime('date_use')->nullable();
            $table->integer('user_use')->unsigned()->nullable();

            $table->foreign('user_use')->references('id')->on('users');

            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('student_id')->references('id')->on('students');

            $table->softDeletes();
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
        Schema::dropIfExists('keys');
    }
}
