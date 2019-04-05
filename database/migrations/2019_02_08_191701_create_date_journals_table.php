<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_journals', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('journal_id')->unsigned();
            $table->boolean('attestation')->default(0);
            $table->date('date')->nullable();

            $table->text('description')->nullable();

            $table->foreign('journal_id')->references('id')->on('sheet_journals');


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
        Schema::dropIfExists('date_journals');
    }
}
