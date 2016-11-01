<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    factory(Jetlag\User::class, 7)->create();
    factory(Jetlag\User::class, 1)->create([
      'email' => 'jetlag@yopmail.com',
      'password' => Hash::make('secret'),
    ]);
  }
}
