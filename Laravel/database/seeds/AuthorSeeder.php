<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AuthorSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    factory(Jetlag\Eloquent\Author::class, 'owner', 7)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer', 7)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader', 7)->create();
    factory(Jetlag\Eloquent\Author::class, 'owner', 3)->create([
      'user_id' => 8,
    ]);
    factory(Jetlag\Eloquent\Author::class, 'writer', 3)->create([
      'user_id' => 8,
    ]);
    factory(Jetlag\Eloquent\Author::class, 'reader', 3)->create([
      'user_id' => 8,
    ]);
  }
}
