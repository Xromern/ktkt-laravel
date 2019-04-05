<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarksJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks_journals', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('student_id')->unsigned();
            $table->integer('journal_id')->unsigned();
            $table->string('mark')->nullable();

            $table->boolean('missed')->nullable();
            $table->integer('date_id')->unsigned();

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('journal_id')->references('id')->on('sheet_journals');
            $table->foreign('date_id')->references('id')->on('date_journals');

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
        Schema::dropIfExists('marks_journals');
    }
}
