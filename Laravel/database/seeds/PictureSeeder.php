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
            'smallPictureLinkId' => 1,
            'mediumPictureLinkId' => 1,
            'bigPictureLinkId' => 1,
            'authorId' => 1,
            'locationId' => 1,
        ]);
    }
}
