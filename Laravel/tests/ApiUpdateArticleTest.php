<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ApiUpdateArticleTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiUpdateFullArticleAsWriter()
  {
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => 13,
      'user_id' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => 13,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'author_id' => 13,
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'author_id' => 13,
      'small_picture_link_id' => $links[0]->id,
      'medium_picture_link_id' => $links[1]->id,
      'big_picture_link_id' => $links[2]->id,
      'article_id' => $article->id,
    ]);

    $this->actingAs($writer)
    ->put($this->articleApiUrl . $article->id, [
      'id' => $article->id,
      'title' => 'article ' . $article->id . ' updated',
      'description_text' => 'some updated description',
      'is_public' => false,
      'is_draft' => false,
      'description_picture' => [
        'id' => $picture->id,
        'small_url' => $links[0]->toArray(),
        'medium_url' => $links[1]->toArray(),
        'big_url' => $links[2]->toArray(),
      ],
      // 'author_users' => [1 => 'writer', 2 => 'owner'],
    ],
    ['ContentType' => 'application/json'])
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id
    ]);
    $this->actingAs($writer)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => 'article ' . $article->id . ' updated',
      'description_text' => 'some updated description',
      'is_public' => false,
      'is_draft' => false,
      'description_picture' => [
        'id' => $picture->id,
        'small_url' => $links[0],
        'medium_url' => $links[1],
        'big_url' => $links[2],
        'place' => null,
      ],
      // 'author_users' => [1 => 'writer', 2 => 'owner', $writer->id => 'writer'],
    ]);
  }

  public function testApiUpdatePartialArticleAsWriter()
  {
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => 14,
      'user_id' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => 14,
      'title' => "article with id 2",
      'description_text' => 'this is some article',
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 2)->create([
      'author_id' => 14,
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'author_id' => 14,
      'small_picture_link_id' => $links[0]->id,
      'medium_picture_link_id' => $links[1]->id,
      'big_picture_link_id' => $links[1]->id,
      'article_id' => $article->id,
    ]);

    $this->actingAs($writer)
    ->patch($this->articleApiUrl . $article->id, [
      'title' => 'article is partially updated',
    ],
    ['ContentType' => 'application/json'])
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => 'article is partially updated',
      'description_text' => 'this is some article',
      'is_draft' => true,
      'is_public' => false,
      'description_picture' => [
        'id' => $picture->id,
        'small_url' => $links[0],
        'medium_url' => $links[1],
        'big_url' => $links[1],
        'place' => null,
      ],
    ]);
    $this->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => 'article is partially updated',
      'description_text' => 'this is some article',
      'is_draft' => true,
      'is_public' => false,
      'description_picture' => [
        'id' => $picture->id,
        'small_url' => $links[0],
        'medium_url' => $links[1],
        'big_url' => $links[1],
        'place' => null,
      ],
      // 'author_users' => [ $writer->id => 'writer'],
    ]);
  }

  public function testApiCannotUpdateArticleAsReader()
  {
    $reader = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'author_id' => 13,
      'user_id' => $reader->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => 13,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    $this->actingAs($reader)
    ->put($this->articleApiUrl . $article->id, [
      'title' => 'article ' . $article->id . ' updated',
      'description_text' => 'some updated description',
      'is_draft' => 0,
      'author_users' => [1 => 'writer', 2 => 'owner'],
    ],
    ['ContentType' => 'application/json'])
    ->assertResponseStatus(403);
  }

  public function testApiCannotUpdateArticleAsLoggedIn()
  {
    $user = factory(Jetlag\User::class)->create();
    $article = factory(Jetlag\Eloquent\Article::class)->create();

    $this->actingAs($user)
    ->put($this->articleApiUrl . $article->id, [
      'title' => 'article ' . $article->id . ' updated',
      'description_text' => 'some updated description',
      'is_draft' => 0,
      'author_users' => [1 => 'writer', 2 => 'owner'],
    ],
    ['ContentType' => 'application/json'])
    ->assertResponseStatus(403);
  }

  public function testApiCannotUpdateArticleWithoutLogin()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'is_public' => true,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    $this->put($this->articleApiUrl . $article->id, [
      'title' => 'article ' . $article->id . ' updated',
      'description_text' => 'some updated description',
      'is_draft' => 0,
      'author_users' => [1 => 'writer', 2 => 'owner'],
    ],
    ['ContentType' => 'application/json'])
    ->assertResponseStatus(403);
  }
}
