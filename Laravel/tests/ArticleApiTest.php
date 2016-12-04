<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ArticleApiTest extends TestCase {

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
      'author_users' => [$owner->id => 'owner', $writer->id => 'writer'],
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
      'author_users' => [$owner->id => 'owner', $writer->id => 'writer'],
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
      'author_users' => [$owner->id => 'owner', $reader->id => 'reader'],
    ]);
  }

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

  public function testApiStoreArticleWithTitle()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [ 'title' => 'article1'], ['ContentType' => 'application/json'])
    ->assertResponseStatus(201);
    $this->seeJson([
      'title' => "article1",
      'id' => 1,
      'description_text' => '',
      'is_draft' => true,
      'is_public' => false,
      'url' => $this->baseUrl . "/article/1",
      // 'author_users' => [ $user->id => 'owner'],
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
      'url' => $this->baseUrl . "/article/1",
      // 'author_users' => [ $user->id => 'owner'],
    ]);
  }

  public function testApiStoreArticleWithoutTitle()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [ 'description_text' => 'an article without a title'], ['ContentType' => 'application/json'])
    ->assertResponseStatus(201);

    $this->seeJson([
      'title' => '',
      'id' => 1,
      'description_text' => 'an article without a title',
      'is_draft' => true,
      'is_public' => false,
      'url' => $this->baseUrl . "/article/1",
    ]);
  }

  public function testApiCannotStoreArticleWithoutLogin()
  {
    $this->post($this->articleApiUrl, [ 'title' => 'article1'], ['ContentType' => 'application/json'])
    ->assertResponseStatus(403);
  }

  public function testApiStoreArticleWithMoreData()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [
      'title' => 'article2',
      'description_text' => 'un bel article, celui-ci',
      'is_draft' => false,
      'author_users' => [1 => 'owner'],
    ],
    ['ContentType' => 'application/json'])
    ->assertResponseStatus(201);
    $this->seeJson([
      'id' => 1,
      'title' => 'article2',
      'description_text' => 'un bel article, celui-ci',
      'is_draft' => false,
      'is_public' => false,
      'url' => $this->baseUrl . "/article/1",
      // 'author_users' => [1 => 'owner', $user->id => 'owner'],
    ]);

    Log::debug("expecting users 1 and " . $user->id . " to be owner of article 1");
    $this->actingAs($user)
    ->get($this->articleApiUrl . 1)
    ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'title' => 'article2',
      'description_text' => 'un bel article, celui-ci',
      'is_draft' => false,
      'is_public' => false,
      'url' => $this->baseUrl . "/article/1",
      // 'author_users' => [1 => 'owner', $user->id => 'owner'],
    ]);
  }

  public function testApiStoreArticleWithPicture()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [
      'title' => 'article1',
      'description_picture' => [
        'url' => [ 'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg' ],
      ],
    ], ['ContentType' => 'application/json'])
    ->assertResponseStatus(201);
    $this->seeJson([
      'id' => 1,
      'title' => "article1",
      'description_text' => '',
      'is_draft' => true,
      'is_public' => false,
      'url' => $this->baseUrl . "/article/1",
      'description_picture' => [
        'id' => 1,
        'small_url' => null,
        'big_url' => null,
        'place' => null,
        'medium_url' => [
          'caption' => '',
          'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
        ]
      ],
    ]);

    $this->actingAs($user)
    ->get($this->articleApiUrl . 1)
    ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'title' => "article1",
      'description_text' => '',
      'is_draft' => true,
      'is_public' => false,
      'url' => $this->baseUrl . "/article/1",
      'description_picture' => [
        'id' => 1,
        'small_url' => null,
        'big_url' => null,
        'medium_url' => [
        'caption' => '',
        'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
        ]
      ],
    ]);
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
