<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ApiStoreArticleTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

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
        'place' => null,
        'medium_url' => [
        'caption' => '',
        'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
        ]
      ],
    ]);
  }

}
