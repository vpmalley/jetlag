<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ApiGetArticlesTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiGetArticlesAsWriter()
  {
    $authorId = 3;
    $writer = factory(Jetlag\User::class)->create();
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'author_id' => $authorId,
      'user_id' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
    ]);

    Log::debug(" expecting author_id=3 and user_id=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
    ->get($this->articleApiUrl)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'url' => $this->baseUrl . "/article/" . $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_draft' => $article->is_draft,
      'is_public' => $article->is_public,
      // 'author_users' => [$owner->id => 'owner', $writer->id => 'writer'],
    ]);
  }

  public function testApiGetArticlesAsOwner()
  {
    $authorId = 4;
    $writer = factory(Jetlag\User::class)->create();
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'author_id' => $authorId,
      'user_id' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
    ]);

    Log::debug(" expecting author_id=3 and user_id=" . $owner->id . " and role=owner for article " . $article->id);
    $this->actingAs($owner)
    ->get($this->articleApiUrl)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'url' => $this->baseUrl . "/article/" . $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      // 'author_users' => [$owner->id => 'owner', $writer->id => 'writer'],
    ]);
  }

  public function testApiGetArticlesAsReader()
  {
    $authorId = 5;
    $reader = factory(Jetlag\User::class)->create();
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'author_id' => $authorId,
      'user_id' => $reader->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'author_id' => $authorId,
      'user_id' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
    ]);

    Log::debug(" expecting author_id=3 and user_id=" . $reader->id . " and role=reader for article " . $article->id);
    $this->actingAs($reader)
    ->get($this->articleApiUrl)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'url' => $this->baseUrl . "/article/" . $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      // 'author_users' => [$owner->id => 'owner', $reader->id => 'reader'],
    ]);
  }
}
