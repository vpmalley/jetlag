<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ArticleSeeder extends Seeder
{
  public function run()
  {
    factory(Jetlag\Eloquent\Article::class, 5)->create();
    factory(Jetlag\Eloquent\Article::class, 5)->create([
      'is_public' => 1,
    ]);
  }
}
