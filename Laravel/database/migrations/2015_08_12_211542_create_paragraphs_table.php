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
            $table->bigInteger('blockContentId');
            $table->string('blockContentType', 15);
            $table->bigInteger('hublotContentId');
            $table->string('hublotContentType', 15);
            $table->bigInteger('place_id');
            $table->date('date');
            $table->string('weather', 20);
            $table->bigInteger('authorId');
            $table->boolean('isDraft');
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
