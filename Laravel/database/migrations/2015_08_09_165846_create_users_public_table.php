<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersPublicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicusers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->bigInteger('userId');
            $table->string('name', 200);
            $table->bigInteger('profilePicId');
            $table->bigInteger('coverPicId');
            $table->string('country', 100);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('publicusers');
    }
}
