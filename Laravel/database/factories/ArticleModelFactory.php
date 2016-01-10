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

$factory->define(Jetlag\Eloquent\Article::class, function (Faker\Generator $faker) {
    return [
      'title' => $faker->realText(50),
      'descriptionText' => $faker->realText(240),
      'isDraft' => 1,
      'isPublic' => 0,
      'authorId' => $faker->randomDigit,
    ];
});

$factory->define(Jetlag\Eloquent\Picture::class, function (Faker\Generator $faker) {
    return [
      'smallPictureLink_id' => 0,
      'mediumPictureLink_id' => 0,
      'bigPictureLink_id' => 0,
      'authorId' => $faker->randomDigit,
      'place_id' => $faker->randomDigit,
    ];
});

$factory->defineAs(Jetlag\Eloquent\Link::class, 'web', function (Faker\Generator $faker) {
    return [
      'caption' => $faker->realText(40),
      'storage' => 'web',
      'url' => $faker->url,
      'authorId' => $faker->randomDigit,
    ];
});
