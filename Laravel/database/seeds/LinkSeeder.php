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
        DB::table('links')->insert([
            'caption' => 'Small pic',
            'storage' => 'web',
            'url' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
            'authorId' => 1,
        ]);
        DB::table('links')->insert([
            'caption' => 'Small pic',
            'storage' => 'web',
            'url' => 'https://images.duckduckgo.com/iu/?u=https%3A%2F%2Fshechive.files.wordpress.com%2F2012%2F11%2Fcoffee-art-28.jpg&f=1',
            'authorId' => 2,
        ]);
    }
}
