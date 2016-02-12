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
    'is_draft' => 1,
    'is_public' => 0,
    'author_id' => $faker->randomDigit,
  ];
});

$factory->define(Jetlag\Eloquent\Picture::class, function (Faker\Generator $faker) {
  return [
    'small_picture_link_id' => $faker->randomDigit,
    'medium_picture_link_id' => $faker->randomDigit,
    'big_picture_link_id' => $faker->randomDigit,
    'author_id' => $faker->randomDigit,
    'place_id' => $faker->randomDigit,
    'article_id' => $faker->randomDigit,
  ];
});

$factory->defineAs(Jetlag\Eloquent\Link::class, 'web', function (Faker\Generator $faker) {
  return [
    'caption' => $faker->realText(40),
    'storage' => 'web',
    'url' => $faker->url,
    'author_id' => $faker->randomDigit,
  ];
});

$factory->define(Jetlag\Eloquent\TextContent::class, function (Faker\Generator $faker) {
  return [
    'author_id' => $faker->randomDigit,
    'content' => $faker->realText(120),
  ];
});

$factory->define(Jetlag\Eloquent\Paragraph::class, function (Faker\Generator $faker) {
  return [
    'title' => $faker->realText(40),
    'place_id' => $faker->randomDigit,
    'weather' => 'sunny',
    'is_draft' => true,
    'author_id' => $faker->randomDigit,
    'article_id' => $faker->randomDigit,
  ];
});

$factory->define(Jetlag\Eloquent\Map::class, function (Faker\Generator $faker) {
  return [
    'caption' => $faker->realText(60),
    'author_id' => $faker->randomDigit,
  ];
});

$factory->define(Jetlag\Eloquent\Marker::class, function (Faker\Generator $faker) {
  return [
    'description' => $faker->realText(40),
    'map_id' => $faker->randomDigit,
    'place_id' => $faker->randomDigit,
    'author_id' => $faker->randomDigit,
  ];
});

$factory->define(Jetlag\Eloquent\Place::class, function (Faker\Generator $faker) {
  return [
    'description' => $faker->realText(70),
    'latitude' => $faker->randomFloat(7, -180, 180),
    'longitude' => $faker->randomFloat(7, -180, 180),
    'altitude' => $faker->randomFloat(1, -1000, 10000),
  ];
});
