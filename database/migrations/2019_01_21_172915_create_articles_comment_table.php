<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_comment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();

            $table->integer('users_id')->unsigned();

            $table->text('content');

            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('users_id')->references('id')->on('users');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_comment');
    }
}
