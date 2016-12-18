<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ApiDeleteArticleTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiDeleteArticleAsOwner()
  {
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'author_id' => 16,
      'user_id' => $owner->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => 16,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    $this->actingAs($owner)
    ->delete($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id
    ]);
    $this->actingAs($owner)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseStatus(404);
  }

  public function testApiCannotDeleteArticleAsWriter()
  {
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => 18,
      'user_id' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => 18,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    $this->actingAs($writer)
    ->delete($this->articleApiUrl . $article->id)
    ->assertResponseStatus(403);
  }

  public function testApiCannotDeleteArticleAsReader()
  {
    $reader = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'author_id' => 18,
      'user_id' => $reader->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => 18,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    $this->actingAs($reader)
    ->delete($this->articleApiUrl . $article->id)
    ->assertResponseStatus(403);
  }

  public function testApiCannotDeleteArticleAsLoggedIn()
  {
    $user = factory(Jetlag\User::class)->create();
    $article = factory(Jetlag\Eloquent\Article::class)->create();

    $this->actingAs($user)
    ->delete($this->articleApiUrl . $article->id)
    ->assertResponseStatus(403);
  }

  public function testApiCannotDeleteArticleWithoutLogin()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create();

    $this->delete($this->articleApiUrl . $article->id)
    ->assertResponseStatus(403);
  }
}
