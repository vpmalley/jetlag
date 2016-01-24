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
      'smallPictureLink_id' => $faker->randomDigit,
      'mediumPictureLink_id' => $faker->randomDigit,
      'bigPictureLink_id' => $faker->randomDigit,
      'authorId' => $faker->randomDigit,
      'place_id' => $faker->randomDigit,
      'article_id' => $faker->randomDigit,
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

$factory->define(Jetlag\Eloquent\TextContent::class, function (Faker\Generator $faker) {
    return [
      'authorId' => $faker->randomDigit,
      'content' => $faker->realText(120),
    ];
});

$factory->define(Jetlag\Eloquent\Paragraph::class, function (Faker\Generator $faker) {
    return [
      'title' => $faker->realText(40),
      'place_id' => $faker->randomDigit,
      'weather' => 'sunny',
      'isDraft' => true,
      'authorId' => $faker->randomDigit,
      'article_id' => $faker->randomDigit,
    ];
});

$factory->define(Jetlag\Eloquent\Place::class, function (Faker\Generator $faker) {
    return [
      'localisation' => $faker->realText(70),
      'latitude' => $faker->randomFloat(7, -180, 180),
      'longitude' => $faker->randomFloat(7, -180, 180),
      'altitude' => $faker->randomFloat(1, -1000, 10000),
    ];
});
