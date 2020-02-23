<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('news_id')->index();
            $table->uuid('user_id')->index();
            $table->text('comment');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 191)->nullable();
            $table->string('updated_by', 191)->nullable();
            $table->string('deleted_by', 191)->nullable();

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
        Schema::dropIfExists('news_comments');
    }
}
