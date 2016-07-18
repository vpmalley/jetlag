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

$factory->defineAs(Jetlag\Eloquent\Author::class, 'owner', function (Faker\Generator $faker) {
  return [
    'author_id' => $faker->randomDigit,
    'user_id' => $faker->randomDigit,
    'role' => 'owner'
  ];
});

$factory->defineAs(Jetlag\Eloquent\Author::class, 'writer', function (Faker\Generator $faker) {
  return [
    'author_id' => $faker->randomDigit,
    'user_id' => $faker->randomDigit,
    'role' => 'writer'
  ];
});

$factory->defineAs(Jetlag\Eloquent\Author::class, 'reader', function (Faker\Generator $faker) {
  return [
    'author_id' => $faker->randomDigit,
    'user_id' => $faker->randomDigit,
    'role' => 'reader'
  ];
});
