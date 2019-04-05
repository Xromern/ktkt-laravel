<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_journals', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique();

            $table->integer('specialty_id')->unsigned();

            $table->integer('teacher_id')->unsigned()->nullable()->comment('curator');

            $table->foreign('specialty_id')->references('id')->on('specialties');

            $table->foreign('teacher_id')->references('id')->on('teachers');

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
        Schema::dropIfExists('group_journals');
    }
}
