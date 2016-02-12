<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParagraphsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paragraphs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title', 200); // to replace with an id to a question
            $table->bigInteger('article_id'); // do we allow a paragraph to appear in multiple articles?
            $table->bigInteger('block_content_id');
            $table->string('block_content_type', 30);
            $table->bigInteger('hubot_content_id');
            $table->string('hubot_content_type', 15);
            $table->bigInteger('place_id');
            $table->date('date');
            $table->string('weather', 20);
            $table->bigInteger('author_id');
            $table->boolean('is_draft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('paragraphs');
    }
}
