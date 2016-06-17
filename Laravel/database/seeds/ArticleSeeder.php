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
            'title' => 'article with id ' . $id,
            'descriptionText' => 'this is a cool article isnt it? id ' . $id,
            'isDraft' => true,
            'authorId' => 1,
        ]);
        factory(Jetlag\Eloquent\Article::class,4)->create();
    }
}
