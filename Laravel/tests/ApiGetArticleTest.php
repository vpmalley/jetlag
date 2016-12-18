<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ApiGetArticleTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiGetArticleAsWriter()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    Log::debug(" expecting author_id=4 and user_id=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_draft' => $article->is_draft,
      'is_public' => $article->is_public,
      // 'author_users' => [$writer->id => 'writer'],
    ]);
  }

  public function testApiGetArticleWithPicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'user_id' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'author_id' => $authorId,
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'author_id' => $authorId,
      'small_picture_link_id' => $links[0]->id,
      'medium_picture_link_id' => $links[1]->id,
      'big_picture_link_id' => $links[2]->id,
      'article_id' => $article->id,
    ]);

    Log::debug(" expecting author_id=4 and user_id=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'description_picture' => [
        'id' => $picture->id,
        'small_url' => $links[0],
        'medium_url' => $links[1],
        'big_url' => $links[2],
        'place' => null,
      ],
      'is_draft' => $article->is_draft,
      'is_public' => $article->is_public,
      // 'author_users' => [$writer->id => 'writer'],
    ]);
  }

  public function testApiGetArticleAsOwner()
  {
    $authorId = 7;
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'author_id' => $authorId,
      'user_id' => $owner->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    Log::debug(" expecting author_id=4 and user_id=" . $owner->id . " and role=owner for article " . $article->id);
    $this->actingAs($owner)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_draft' => $article->is_draft,
      'is_public' => $article->is_public,
      // 'author_users' => [$owner->id => 'owner'],
    ]);
  }

  public function testApiGetArticleAsReader()
  {
    $authorId = 6;
    $reader = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'author_id' => $authorId,
      'user_id' => $reader->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2'
    ]);

    Log::debug(" expecting author_id=4 and user_id=" . $reader->id . " and role=reader for article " . $article->id);
    $this->actingAs($reader)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_draft' => $article->is_draft,
      'is_public' => $article->is_public,
      // 'author_users' => [$reader->id => 'reader'],
    ]);
  }

  public function testApiGetPublicArticle()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_public' => true,
    ]);

    $this->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_draft' => $article->is_draft,
      'is_public' => true,
    ]);
  }

  public function testApiCannotGetPrivateArticleWithoutLogin()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_public' => false,
    ]);

    $this->get($this->articleApiUrl . $article->id)
    ->assertResponseStatus(403);
  }

  public function testApiGetStoredArticleAsReader()
  {
    $owner = factory(Jetlag\User::class)->create();
    $reader = factory(Jetlag\User::class)->create();

    $this->actingAs($owner)
    ->post($this->articleApiUrl, [
      'title' => 'article1',
      'author_users' => [$reader->id => 'reader'],
    ], ['ContentType' => 'application/json'])
    ->assertResponseStatus(201);
    $this->seeJson([
      'id' => 1,
      'url' => $this->baseUrl . "/article/1",
    ]);

    Log::debug("expecting user " . $reader->id . " to be reader of article 1");
    $this->actingAs($reader)
    ->get($this->articleApiUrl . 1)
    ->assertResponseOk();
    $this->seeJson([
      'title' => "article1",
      'id' => 1,
      'description_text' => '',
      'is_draft' => true,
      'is_public' => false,
      // 'author_users' => [ $owner->id => 'owner', $reader->id => 'reader'],
    ]);
  }

  public function testApiCannotGetStoredPrivateArticle()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [ 'title' => 'article1'], ['ContentType' => 'application/json'])
    ->assertResponseStatus(201);
    $this->seeJson([
      'id' => 1,
      'url' => $this->baseUrl . "/article/1",
    ]);

    Log::debug("expecting user " . $user->id . " to be owner of article 1");
    $this->actingAs($user)
    ->get($this->articleApiUrl . 1)
    ->assertResponseOk();
    $this->seeJson([
      'title' => "article1",
      'id' => 1,
      'description_text' => '',
      'is_draft' => true,
      'is_public' => false,
      // 'author_users' => [ $user->id => 'owner'],
    ]);
  }
}
