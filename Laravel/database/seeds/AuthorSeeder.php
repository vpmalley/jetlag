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
        DB::table('authors')->insert([
            'authorId' => 1,
            'userId' => 1,
            'role' => 'owner',
        ]);
        DB::table('authors')->insert([
            'authorId' => 1,
            'userId' => 2,
            'role' => 'writer',
        ]);
    }
}
