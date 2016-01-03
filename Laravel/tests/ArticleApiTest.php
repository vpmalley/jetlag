<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ArticleApiTest extends TestCase {

  use WithoutMiddleware;
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/article/";

  public function testApiGetArticles()
  {
    $writer = factory(Jetlag\User::class)->create();
    $owner = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => 3,
      'userId' => $writer->id
    ]);
    factory(Jetlag\Eloquent\Author::class, 'owner')->create([
      'authorId' => 3,
      'userId' => $owner->id
    ]);

    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 3,
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
    //$this->dump();
  }

  public function testApiGetArticleAsWriter()
  {
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'authorId' => 4,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'authorId' => 4,
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

  public function testApiGetPublicArticle()
  {
    $user = factory(Jetlag\User::class)->create();
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
      'isPublic' => true,
    ]);

    $this->actingAs($user)
      ->get($this->articleApiUrl . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'isPublic' => 1,
      ]);
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

  public function testApiUpdateFullArticle()
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

  public function testApiUpdatePartialArticle()
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
        'bigUrl' => null,
      ],
      'authorUsers' => [ $writer->id => 'writer'],
    ]);
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
}
