<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class PictureUploadTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $pictureStorageApiUrl = "/api/0.1/pix/";

  public function testApiUpdatePicture()
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
    $file = new UploadedFile(
                public_path() . '/images/4.jpg',
                '4.jpg',
                'image/jpeg',
                filesize(public_path() . '/images/4.jpg'),
                null,
                true // for $test
            );

    Log::debug(" expecting to store picture " . $picture->id . " from file " . public_path() . '/images/4.jpg');
    $this->actingAs($writer)
    ->post($this->pictureStorageApiUrl . $picture->id . '/upload', [
      'caption' => 'a different caption',
      'file' => $file,
    ])
    ->assertResponseOk();
    $this->seeJson([
      'id' => $picture->id,
      'small_url' => $links[0],
      'medium_url' => $links[1],
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'pix/' . $user->id . '/pik' . $picture->id . '.txt',
      ],
      'place' => $places[0],
    ]);
  }


  public function testApiUnknownPicture()
  {
    Log::debug(" expecting to fail storing picture (400 wrong picture)");
    $this
    ->post($this->pictureStorageApiUrl . $picture->id . '/upload', [
      'file' => 'some invalid content',
    ])
    ->assertResponseStatus(400);
  }

  public function testApiWrongFormatOfPictureFile()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'author_id' => $authorId,
    ]);

    Log::debug(" expecting to fail storing picture " . $picture->id . " (400 wrong format)");
    $this->actingAs($writer)
    ->post($this->pictureStorageApiUrl . $picture->id . '/upload', [
      'file' => 'some content of picture',
    ])
    ->assertResponseStatus(400);
  }

  public function testApiUserHasNoWritingRight()
  {
    $picture = factory(Jetlag\Eloquent\Picture::class)->create();

    Log::debug(" expecting to fail storing picture " . $picture->id . " (403 no writing right)");
    $this
    ->post($this->pictureStorageApiUrl . $picture->id . '/upload', [
      'file' => 'some content of picture',
    ])
    ->assertResponseStatus(403);
  }

}
