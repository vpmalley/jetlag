<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class PictureUploadNewTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $pictureStorageApiUrl = "/api/0.1/pix/upload";

  public function testApiUpdatePicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    $file = new UploadedFile(
                public_path() . '/images/4.jpg',
                '4.jpg',
                'image/jpeg',
                filesize(public_path() . '/images/4.jpg'),
                null,
                true // for $test
            );

    Log::debug(" expecting to store picture from file " . public_path() . '/images/4.jpg');
    $this->actingAs($writer)
    ->post($this->pictureStorageApiUrl, [
      'caption' => 'a different caption',
      'file' => $file,
    ])
    ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'big_url' => [
        'caption' => 'a different caption',
        'url' => 'pix/' . $user->id . '/pik1.txt',
      ],
    ]);
  }

  public function testApiWrongFormatOfPictureFile()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);

    Log::debug(" expecting to fail storing picture (400 wrong format)");
    $this->actingAs($writer)
    ->post($this->pictureStorageApiUrl, [
      'file' => 'some content of picture',
    ])
    ->assertResponseStatus(400);
  }

  public function testApiUserHasNoWritingRight()
  {
    $picture = factory(Jetlag\Eloquent\Picture::class)->create();

    Log::debug(" expecting to fail storing picture (403 no writing right)");
    $this
    ->post($this->pictureStorageApiUrl, [
      'file' => 'some content of picture',
    ])
    ->assertResponseStatus(403);
  }

}
