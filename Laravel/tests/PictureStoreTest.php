<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class PictureStoreTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $picturesApiUrl = "/api/0.1/pictures/";

  public function testStoreBigUrlPicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);

    Log::debug(" expecting to store picture ");
    $this->actingAs($writer)
    ->post($this->picturesApiUrl, [
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
    ])
    ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'small_url' => null,
      'medium_url' => null,
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
      'place' => null,
    ]);
  }

  public function testStoreEntirePicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);

    Log::debug(" expecting to store picture.");
    $this->actingAs($writer)
    ->post($this->picturesApiUrl, [
      'small_url' => [
        'caption' => 'a small url caption',
        'url' => 'http://lorempixel.com/400/200/sports',
      ],
      'medium_url' => [
        'caption' => 'a medium caption',
        'url' => 'http://lorempixel.com/400/200/business',
      ],
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
      'place' => [
        'altitude' => 123.4,
        'latitude' => 45.2,
        'longitude' => 101.3,
        'description' => 'lala',
      ]
    ])
    ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'small_url' => [
        'caption' => 'a small url caption',
        'url' => 'http://lorempixel.com/400/200/sports',
      ],
      'medium_url' => [
        'caption' => 'a medium caption',
        'url' => 'http://lorempixel.com/400/200/business',
      ],
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
      'place' => [
        'altitude' => 123.4,
        'latitude' => 45.2,
        'longitude' => 101.3,
        'description' => 'lala',
      ]
    ]);
  }

}
