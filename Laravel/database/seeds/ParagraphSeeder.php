<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Jetlag\Eloquent\Paragraph;

class ParagraphSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    factory(Jetlag\Eloquent\Paragraph::class, 20)->create();
  }
}
