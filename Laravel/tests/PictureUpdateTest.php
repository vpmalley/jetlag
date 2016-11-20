<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class PictureUpdateTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $picturesApiUrl = "/api/0.1/pictures/";

  public function testUpdateBigUrlPicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'author_id' => $authorId,
    ]);
    $places = factory(Jetlag\Eloquent\Place::class, 2)->create();
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'author_id' => $authorId,
      'small_picture_link_id' => $links[0]->id,
      'medium_picture_link_id' => $links[1]->id,
      'big_picture_link_id' => $links[2]->id,
      'place_id' => $places[0]->id,
    ]);

    Log::debug(" expecting to update picture " . $picture->id);
    $this->actingAs($writer)
    ->put($this->picturesApiUrl . $picture->id, [
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
    ])
    ->assertResponseOk();
    $this->seeJson([
      'id' => $picture->id,
      'small_url' => $links[0],
      'medium_url' => $links[1],
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
    ]);
  }

  public function testUpdateEntirePicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'author_id' => $authorId,
    ]);
    $places = factory(Jetlag\Eloquent\Place::class, 2)->create();
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'author_id' => $authorId,
      'small_picture_link_id' => $links[0]->id,
      'medium_picture_link_id' => $links[1]->id,
      'big_picture_link_id' => $links[2]->id,
      'place_id' => $places[0]->id,
    ]);

    Log::debug(" expecting to update picture " . $picture->id);
    $this->actingAs($writer)
    ->put($this->picturesApiUrl . $picture->id, [
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
      'id' => $picture->id,
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

  public function testApiUnknownPicture()
  {
    Log::debug(" expecting to fail updating picture");
    $this
    ->put($this->picturesApiUrl . '1234', [])
    ->assertResponseStatus(404);
  }

  public function testApiUserHasNoWritingRight()
  {
    $picture = factory(Jetlag\Eloquent\Picture::class)->create();

    Log::debug(" expecting to fail storing picture " . $picture->id);
    $this
    ->put($this->picturesApiUrl . $picture->id, [
      'big_url' => [
        'caption' => 'another caption',
        'url' => 'http://lorempixel.com/400/200',
      ],
    ])
    ->assertResponseStatus(403);
  }

}
