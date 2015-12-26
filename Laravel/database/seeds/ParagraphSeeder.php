<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ParagraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = 2;
        DB::table('paragraphs')->insert([
            'title' => 'un beau paragraphe ' . str_random(10),
            'article_id' => rand(1,10),
            'locationId' => 1,
            'weather' => 'sunny',
            'isDraft' => true,
            'authorId' => 1,
        ]);
    }
}
