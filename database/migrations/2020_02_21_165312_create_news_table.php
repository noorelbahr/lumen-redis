<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('slug', 191);
            $table->string('title', 255);
            $table->longText('content');
            $table->string('heading_image', 191)->nullable();
            $table->string('tags', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 191)->nullable();
            $table->string('updated_by', 191)->nullable();
            $table->string('deleted_by', 191)->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
