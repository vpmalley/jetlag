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
/* XXX: was in 201601-creators--   
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
*/
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    factory(Jetlag\Eloquent\Article::class, 3)->create();
    factory(Jetlag\Eloquent\Article::class, 3)->create([
      'is_public' => 1,
    ]);
  }
}
