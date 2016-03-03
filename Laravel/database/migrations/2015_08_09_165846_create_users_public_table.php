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
            $table->bigInteger('id');
            $table->unique('id');
            $table->primary('id');
            $table->timestamps();
            $table->string('name', 200);
            $table->string('profile_pic_url', 200);
            $table->string('country', 100);
            $table->string('city', 100);
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
