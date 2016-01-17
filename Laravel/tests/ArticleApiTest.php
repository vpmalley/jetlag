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
      'authorId' => $authorId,
      'userId' => $writer->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'authorId' => $authorId,
      'userId' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
    ]);

    Log::debug(" expecting authorId=3 and userId=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
      ->get($this->articleApiUrl)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'url' => $this->baseUrl . "/article/" . $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'authorUsers' => [$owner->id => 'owner', $writer->id => 'writer'],
      ]);
  }

  public function testApiGetArticlesAsOwner()
  {
    $authorId = 4;
    $writer = factory(Jetlag\User::class)->create();
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => $authorId,
      'userId' => $writer->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'authorId' => $authorId,
      'userId' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
    ]);

    Log::debug(" expecting authorId=3 and userId=" . $owner->id . " and role=owner for article " . $article->id);
    $this->actingAs($owner)
      ->get($this->articleApiUrl)
      ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'url' => $this->baseUrl . "/article/" . $article->id,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
      'authorUsers' => [$owner->id => 'owner', $writer->id => 'writer'],
    ]);
  }

  public function testApiGetArticlesAsReader()
  {
    $authorId = 5;
    $reader = factory(Jetlag\User::class)->create();
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'authorId' => $authorId,
      'userId' => $reader->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'authorId' => $authorId,
      'userId' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
    ]);

    Log::debug(" expecting authorId=3 and userId=" . $reader->id . " and role=reader for article " . $article->id);
    $this->actingAs($reader)
      ->get($this->articleApiUrl)
      ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'url' => $this->baseUrl . "/article/" . $article->id,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
      'authorUsers' => [$owner->id => 'owner', $reader->id => 'reader'],
    ]);
  }

  public function testApiGetArticleAsWriter()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => $authorId,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);

    Log::debug(" expecting authorId=4 and userId=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
      ->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'authorUsers' => [$writer->id => 'writer'],
      ]);
  }

  public function testApiGetArticleWithPicture()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => $authorId,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'authorId' => $authorId,
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'authorId' => $authorId,
      'smallPictureLink_id' => $links[0]->id,
      'mediumPictureLink_id' => $links[1]->id,
      'bigPictureLink_id' => $links[2]->id,
      'article_id' => $article->id,
    ]);

    Log::debug(" expecting authorId=4 and userId=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
      ->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'descriptionMedia' => [
          'id' => $picture->id,
          'smallUrl' => $links[0]->url,
          'mediumUrl' => $links[1]->url,
          'bigUrl' => $links[2]->url,
        ],
        'isDraft' => 1, // why not true?
        'authorUsers' => [$writer->id => 'writer'],
      ]);
  }

  public function testApiGetArticleWithParagraph()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => $authorId,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'authorId' => $authorId,
    ]);
    $places = factory(Jetlag\Eloquent\Place::class, 2)->create();
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'authorId' => $authorId,
      'smallPictureLink_id' => $links[0]->id,
      'mediumPictureLink_id' => $links[1]->id,
      'bigPictureLink_id' => $links[2]->id,
      'place_id' => $places[0]->id,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'blockContentId' => $picture->id,
      'place_id' => $places[1]->id,
    ]);

    Log::debug(" expecting authorId=4 and userId=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
      ->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'authorUsers' => [$writer->id => 'writer'],
        'paragraphs' => [
          [
            'id' => 1,
            'title' => 'A first paragraph',
            'block_content' => [
              'id' => $picture->id,
              'small_url' => [ 'caption' => $links[0]->caption, 'url' => $links[0]->url ],
              'medium_url' => [ 'caption' => $links[1]->caption, 'url' => $links[1]->url ],
              'big_url' => [ 'caption' => $links[2]->caption, 'url' => $links[2]->url ],
              'place' => [
                'localisation' => $places[0]->localisation,
                'latitude' => $places[0]->latitude,
                'longitude' => $places[0]->longitude,
                'altitude' => $places[0]->altitude,
              ]
            ],
            'weather' => 'cloudy',
            'date' => '2016-01-03',
            'isDraft' => 1,
            'place' => [
              'localisation' => $places[1]->localisation,
              'latitude' => $places[1]->latitude,
              'longitude' => $places[1]->longitude,
              'altitude' => $places[1]->altitude,
            ]
          ]
        ],
      ]);
  }

  public function testApiGetArticleAsOwner()
  {
    $authorId = 7;
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'authorId' => $authorId,
      'userId' => $owner->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);

    Log::debug(" expecting authorId=4 and userId=" . $owner->id . " and role=owner for article " . $article->id);
    $this->actingAs($owner)
      ->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'authorUsers' => [$owner->id => 'owner'],
      ]);
  }

  public function testApiGetArticleAsReader()
  {
    $authorId = 6;
    $reader = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'authorId' => $authorId,
      'userId' => $reader->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);

    Log::debug(" expecting authorId=4 and userId=" . $reader->id . " and role=reader for article " . $article->id);
    $this->actingAs($reader)
      ->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'authorUsers' => [$reader->id => 'reader'],
      ]);
  }

  public function testApiGetPublicArticle()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
      'isPublic' => true,
    ]);

    $this->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'isPublic' => 1,
      ]);
  }

  public function testApiCannotGetPrivateArticleWithoutLogin()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
      'isPublic' => false,
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
        'descriptionText' => '',
        'isDraft' => 1,
        'authorUsers' => [ $user->id => 'owner'],
      ]);
  }

  public function testApiCannotStoreArticleWithoutLogin()
  {
    $this->post($this->articleApiUrl, [ 'title' => 'article1'], ['ContentType' => 'application/json'])
      ->assertResponseStatus(403);
  }

  public function testApiCannotStoreArticleWithoutTitle()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
      ->post($this->articleApiUrl, [ 'descriptionText' => 'an article without a title'], ['ContentType' => 'application/json'])
      ->assertResponseStatus(400);

    $this->actingAs($user)
      ->get($this->articleApiUrl . 1)
      ->assertResponseStatus(404);
  }

  public function testApiStoreArticleWithMoreData()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
      ->post($this->articleApiUrl, [
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      'authorUsers' => [1 => 'owner'],
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseStatus(201);
    $this->seeJson([
        'id' => 1,
        'url' => $this->baseUrl . "/article/1",
      ]);

    Log::debug("expecting users 1 and " . $user->id . " to be owner of article 1");
    $this->actingAs($user)
      ->get($this->articleApiUrl . 1)
      ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      'authorUsers' => [1 => 'owner', $user->id => 'owner'],
      ]);
  }

  public function testApiStoreArticleWithPicture()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
      ->post($this->articleApiUrl, [
      'title' => 'article1',
      'descriptionMedia' => [
        'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
        ],
      ], ['ContentType' => 'application/json'])
      ->assertResponseStatus(201);
    $this->seeJson([
        'id' => 1,
        'url' => $this->baseUrl . "/article/1",
      ]);

    $this->actingAs($user)
      ->get($this->articleApiUrl . 1)
      ->assertResponseOk();
    $this->seeJson([
        'id' => 1,
        'title' => "article1",
        'descriptionText' => '',
        'isDraft' => 1,
        'descriptionMedia' => [
          'id' => 1,
          'smallUrl' => null,
          'bigUrl' => null,
          'mediumUrl' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
          ],
      ]);
  }

  public function testApiStoreArticleWithParagraph()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
      ->post($this->articleApiUrl, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'title' => 'A first paragraph',
          'block_content' => [
            'big_url' => [
                'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
              ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
        ]
      ],
    ], ['ContentType' => 'application/json'])
      ->assertResponseStatus(201);
    $this->seeJson([
        'id' => 1,
        'url' => $this->baseUrl . "/article/1",
      ]);

    $this->actingAs($user)
      ->get($this->articleApiUrl . 1)
      ->assertResponseOk();
    $this->seeJson([
    'title' => 'article1',
    'descriptionText' => '',
    'isDraft' => 1,
    'descriptionMedia' => [],
    'paragraphs' => [
      [
        'id' => 1,
        'title' => 'A first paragraph',
        'block_content' => [
          'big_url' => [
            'caption' => '',
            'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
          ]
        ],
        'weather' => 'cloudy',
        'date' => '2016-01-03',
        'isDraft' => 1,
        'place' => null,
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
        'authorUsers' => [$reader->id => 'reader'],
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
        'descriptionText' => '',
        'isDraft' => 1,
        'authorUsers' => [ $owner->id => 'owner', $reader->id => 'reader'],
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
        'descriptionText' => '',
        'isDraft' => 1,
        'authorUsers' => [ $user->id => 'owner'],
      ]);
  }

  public function testApiUpdateFullArticleAsWriter()
  {
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => 13,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 13,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'authorId' => 13,
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'authorId' => 13,
      'smallPictureLink_id' => $links[0]->id,
      'mediumPictureLink_id' => $links[1]->id,
      'bigPictureLink_id' => $links[2]->id,
      'article_id' => $article->id,
    ]);

    $this->actingAs($writer)
      ->put($this->articleApiUrl . $article->id, [
      'title' => 'article ' . $article->id . ' updated',
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'authorUsers' => [1 => 'writer', 2 => 'owner'],
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
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'descriptionMedia' => [
        'id' => $picture->id,
        'smallUrl' => $links[0]->url,
        'mediumUrl' => $links[1]->url,
        'bigUrl' => $links[2]->url,
      ],
      'authorUsers' => [1 => 'writer', 2 => 'owner', $writer->id => 'writer'],
    ]);
  }

  public function testApiUpdatePartialArticleAsWriter()
  {
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => 14,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 14,
      'title' => "article with id 2",
      'descriptionText' => 'this is some article',
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 2)->create([
      'authorId' => 14,
    ]);
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'authorId' => 14,
      'smallPictureLink_id' => $links[0]->id,
      'mediumPictureLink_id' => $links[1]->id,
      'bigPictureLink_id' => $links[1]->id,
      'article_id' => $article->id,
    ]);

    $this->actingAs($writer)
      ->patch($this->articleApiUrl . $article->id, [
      'title' => 'article is partially updated',
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id
      ]);
    $this->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => 'article is partially updated',
      'descriptionText' => 'this is some article',
      'isDraft' => 1,
      'descriptionMedia' => [
        'id' => $picture->id,
        'smallUrl' => $links[0]->url,
        'mediumUrl' => $links[1]->url,
        'bigUrl' => $links[1]->url,
      ],
      'authorUsers' => [ $writer->id => 'writer'],
    ]);
  }

  public function testApiCannotUpdateArticleAsReader()
  {
    $reader = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'authorId' => 13,
      'userId' => $reader->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 13,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);

    $this->actingAs($reader)
      ->put($this->articleApiUrl . $article->id, [
      'title' => 'article ' . $article->id . ' updated',
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'authorUsers' => [1 => 'writer', 2 => 'owner'],
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
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'authorUsers' => [1 => 'writer', 2 => 'owner'],
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseStatus(403);
  }

  public function testApiCannotUpdateArticleWithoutLogin()
  {
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'isPublic' => true,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);

    $this->put($this->articleApiUrl . $article->id, [
      'title' => 'article ' . $article->id . ' updated',
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'authorUsers' => [1 => 'writer', 2 => 'owner'],
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseStatus(403);
  }

  public function testApiDeleteArticleAsOwner()
  {
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'authorId' => 16,
      'userId' => $owner->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 16,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
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
      'authorId' => 18,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 18,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);

    $this->actingAs($writer)
      ->delete($this->articleApiUrl . $article->id)
      ->assertResponseStatus(403);
  }

  public function testApiCannotDeleteArticleAsReader()
  {
    $reader = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'reader')->create([
      'authorId' => 18,
      'userId' => $reader->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 18,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
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
