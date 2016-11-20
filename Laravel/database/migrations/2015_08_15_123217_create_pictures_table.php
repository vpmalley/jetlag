<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->bigInteger('small_picture_link_id')->nullable();
            $table->bigInteger('medium_picture_link_id')->nullable();
            $table->bigInteger('big_picture_link_id')->nullable();
            $table->boolean('is_public');
            $table->bigInteger('author_id');
            $table->bigInteger('place_id')->nullable();
            $table->bigInteger('article_id')->nullable();
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
        Schema::drop('pictures');
    }
}
