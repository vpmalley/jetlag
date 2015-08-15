<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = 2;
        DB::table('articles')->insert([
            'title' => 'id ' . $id . str_random(30),
            'descriptionText' => 'id ' . $id . str_random(10) . ' ' . str_random(20),
            'isDraft' => true,
            'authorId' => 1,
        ]);
    }
}
