<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PictureSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    factory(Jetlag\Eloquent\Picture::class, 9)->create();
  }
}
