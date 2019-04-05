<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_infos', function (Blueprint $table) {
            $table->increments('id');


            $table->integer('student_id')->unsigned();

            $table->string('email')->nullable();
            $table->string('email_parents')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->boolean('sex')->nullable()->comment('0 - жінка,1 - чоловік');
            $table->text('tale')->nullable();

            $table->foreign('student_id')->references('id')->on('students');

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
        Schema::dropIfExists('students_infos');
    }
}
