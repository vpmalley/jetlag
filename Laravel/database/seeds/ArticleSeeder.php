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
            'descriptionMediaId' => 1,
            'isDraft' => true,
            'authorId' => 1,
        ]);
    }
}
