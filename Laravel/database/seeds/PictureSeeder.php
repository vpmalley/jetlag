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
        DB::table('pictures')->insert([
            'smallPictureLink_id' => 1,
            'mediumPictureLink_id' => 1,
            'bigPictureLink_id' => 1,
            'authorId' => 1,
            'place_id' => 1,
            'article_id' => 1,
        ]);
        DB::table('pictures')->insert([
            'smallPictureLink_id' => 1,
            'mediumPictureLink_id' => 1,
            'bigPictureLink_id' => 1,
            'authorId' => 1,
            'place_id' => 1,
        ]);
    }
}
