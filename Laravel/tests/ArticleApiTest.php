<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ArticleApiTest extends TestCase {

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
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);
    $this->baseUrl = "http://homestead.app";

    Log::debug(" expecting authorId=3 and userId=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
      ->get('/api/article')
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

  public function testApiGetArticle()
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
    $this->baseUrl = "http://homestead.app";

    Log::debug(" expecting authorId=4 and userId=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
      ->get("/api/article/" . $article->id)
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        'authorUsers' => [$writer->id => 'writer'],
      ]);
  }

  public function testApiStoreArticleWithTitle()
  {
    $user = factory(Jetlag\User::class)->create();
    $this->baseUrl = "http://homestead.app";

    $this->actingAs($user)
      ->post('/api/article', [ 'title' => 'article1'], ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 3,
      ]);

    Log::debug("expecting user " . $user->id . " to be owner of article 3");
    $this->actingAs($user)
      ->get('/api/article/3')
      ->assertResponseOk();
    $this->seeJson([
        'title' => "article1",
        'id' => 3,
        'descriptionText' => '',
        'isDraft' => 1,
        'authorUsers' => [ $user->id => 'owner'],
      ]);
  }

  public function testApiStoreArticleWithMoreData()
  {
    $user = factory(Jetlag\User::class)->create();
    $this->baseUrl = "http://homestead.app";

    $this->actingAs($user)
      ->post('/api/article', [
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      'authorUsers' => [1 => 'owner'],
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 4,
      ]);

    Log::debug("expecting users 1 and " . $user->id . " to be owner of article 4");
    $this->actingAs($user)
      ->get('/api/article/4')
      ->assertResponseOk();
    $this->seeJson([
      'id' => 4,
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      'authorUsers' => [1 => 'owner', $user->id => 'owner'],
      ]);
  }

  public function testApiStoreArticleWithPicture()
  {
    $user = factory(Jetlag\User::class)->create();
    $this->baseUrl = "http://homestead.app";
    $this->actingAs($user)
      ->post('/api/article', [
      'title' => 'article1',
      'descriptionMedia' => [
        'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
        ],
      ], ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 5,
      ]);

    $this->actingAs($user)
      ->get('/api/article/5')
      ->assertResponseOk();
    $this->seeJson([
        'id' => 5,
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
    $this->baseUrl = "http://homestead.app";

    $this->actingAs($writer)
      ->put('/api/article/' . $article->id, [
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
      ->get('/api/article/' . $article->id)
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
    $this->baseUrl = "http://homestead.app";
    $this->actingAs($writer)
      ->put('/api/article/' . $article->id, [
      'title' => 'article is partially updated',
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => $article->id
      ]);
    $this->get('/api/article/'. $article->id)
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
    $this->baseUrl = "http://homestead.app";
    $this->actingAs($owner)
      ->delete('/api/article/' . $article->id)
      ->assertResponseOk();
      // TODO test missing article
    // $this->actingAs($owner)
    //   ->get('/api/article/' . $article->id)
    //   ->assertResponseseStatus(404);
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
    $this->baseUrl = "http://homestead.app";
    $this->actingAs($writer)
      ->delete('/api/article/' . $article->id)
      ->assertResponseStatus(403);
  }
}
