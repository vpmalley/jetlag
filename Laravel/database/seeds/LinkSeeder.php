<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LinkSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    factory(Jetlag\Eloquent\Link::class, 'web', 2)->create([
      'url' => 'https://images.duckduckgo.com/iu/?u=https%3A%2F%2Fshechive.files.wordpress.com%2F2012%2F11%2Fcoffee-art-28.jpg&f=1',
    ]);
    factory(Jetlag\Eloquent\Link::class, 'web', 2)->create([
      'url' => 'https://farm6.staticflickr.com/5827/21769614873_f002b6dacc_q_d.jpg',
    ]);
    factory(Jetlag\Eloquent\Link::class, 'web', 2)->create([
      'url' => 'https://farm1.staticflickr.com/690/21454244013_971a0cf0a8_q_d.jpg',
    ]);
    factory(Jetlag\Eloquent\Link::class, 'web', 4)->create();
  }
}
