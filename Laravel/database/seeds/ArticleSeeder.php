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
        factory(Jetlag\Eloquent\Article::class, 3)->create();
        factory(Jetlag\Eloquent\Article::class, 3)->create([
          'isPublic' => 1,
        ]);
    }
}
