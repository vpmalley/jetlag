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
            $table->bigInteger('small_picture_link_id');
            $table->bigInteger('medium_picture_link_id');
            $table->bigInteger('big_picture_link_id');
            $table->bigInteger('authorId');
            $table->bigInteger('place_id');
            $table->bigInteger('article_id')->nullable();
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
