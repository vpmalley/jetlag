<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use Symfony\Component\Console\Output\ConsoleOutput;

$factory->define(Jetlag\User::class, function (Faker\Generator $faker) {
  $email = $faker->email;
  $password = str_random(10);
  $output = new ConsoleOutput();
  $output->writeln("Created user with credentials " . $email . " / ". $password);
  return [
    'name' => $faker->name,
    'email' => $email,
    'password' => Hash::make($password),
    'remember_token' => str_random(10),
  ];
});
