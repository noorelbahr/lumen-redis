<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug', 191);
            $table->string('name', 191);
            $table->text('permissions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 191)->nullable();
            $table->string('updated_by', 191)->nullable();
            $table->string('deleted_by', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
