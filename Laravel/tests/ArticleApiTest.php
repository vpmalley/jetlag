<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArticleApiTest extends TestCase {

  public function testApiGetArticles()
  {
    $this->baseUrl = "http://homestead.app";
    $this->get('/api/article')
      ->assertResponseOk();
    $this->seeJson([[
        'id' => 1,
        'url' => $this->baseUrl . "/article/1",
      ]]);
    //$this->dump();
  }

  public function testApiGetFirstArticle()
  {
    $this->baseUrl = "http://homestead.app";
    $this->get('/api/article/1')
      ->assertResponseOk();
    $this->seeJson([
        'id' => 1,
        'title' => "article with id 2",
        'descriptionText' => 'this is a cool article isnt it? id 2',
        'isDraft' => 1, // why not true?
        //'authorUserIds' => [1],
      ]);
  }

  public function testApiStoreArticleWithTitle()
  {
    $this->baseUrl = "http://homestead.app";
    $this->post('/api/article', [ 'title' => 'article1'], ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 2,
      ]);

    $this->get('/api/article/2')
      ->assertResponseOk();
    $this->seeJson([
        'id' => 2,
        'title' => "article1",
        'descriptionText' => '',
        'isDraft' => 1,
        'authorUserIds' => [],
      ]);
  }

  public function testApiStoreArticleWithMoreData()
  {
    $this->baseUrl = "http://homestead.app";
    $this->post('/api/article', [
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      'authorUserIds' => [1, 2],
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 3,
      ]);

    $this->get('/api/article/3')
      ->assertResponseOk();
    $this->seeJson([
      'id' => 3,
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      //'authorUserIds' => [1, 2],
      ]);
  }

  public function testApiStoreArticleWithPicture()
  {
    $this->baseUrl = "http://homestead.app";
    $this->post('/api/article', [
      'title' => 'article1',
      'descriptionMedia' => [
        'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
        ],
      ], ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 4,
      ]);

    $this->get('/api/article/4')
      ->assertResponseOk();
    $this->seeJson([
        'id' => 4,
        'title' => "article1",
        'descriptionText' => '',
        'isDraft' => 1,
        //'authorUserIds' => [],
        'descriptionMedia' => [
          'id' => 3,
          'smallUrl' => null,
          'bigUrl' => null,
          'mediumUrl' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
          ],
      ]);
  }

  public function testApiUpdateFirstArticle()
  {
    $this->baseUrl = "http://homestead.app";
    $this->put('/api/article/1', [
      'title' => 'article1 updated',
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'authorUserIds' => [1, 2],
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 1
      ]);
    $this->get('/api/article/1')
      ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'title' => 'article1 updated',
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'descriptionMedia' => [
        'id' => 1,
        'smallUrl' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
        'bigUrl' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
        'mediumUrl' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
      ],
      //'authorUserIds' => [1, 2],
    ]);
  }

  public function testApiPartUpdateFirstArticle()
  {
    $this->baseUrl = "http://homestead.app";
    $this->put('/api/article/1', [
      'title' => 'article1 is again updated',
      ],
      ['ContentType' => 'application/json'])
      ->assertResponseOk();
    $this->seeJson([
        'id' => 1
      ]);
    $this->get('/api/article/1')
      ->assertResponseOk();
    $this->seeJson([
      'id' => 1,
      'title' => 'article1 is again updated',
      'descriptionText' => 'some updated description',
      'isDraft' => 0,
      'descriptionMedia' => [
        'id' => 1,
        'smallUrl' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
        'bigUrl' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
        'mediumUrl' => 'https://images.duckduckgo.com/iu/?u=http%3A%2F%2Fimages.smh.com.au%2F2011%2F07%2F15%2F2494516%2Fth-coffee-420x0.jpg&f=1',
      ],
      //'authorUserIds' => [1, 2],
    ]);
  }

  public function testApiDeleteArticle2()
  {
    $this->baseUrl = "http://homestead.app";
    $this->delete('/api/article/2')
      ->assertResponseOk();
    $this->get('/api/article/2')
      ->assertResponseseStatus(404);
  }
}
