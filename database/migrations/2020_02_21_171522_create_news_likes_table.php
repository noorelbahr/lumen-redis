<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('news_id')->index();
            $table->uuid('user_id')->index();
            $table->timestamps();
            $table->string('created_by', 191)->nullable();
            $table->string('updated_by', 191)->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('news_id')->references('id')->on('news');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_likes');
    }
}
