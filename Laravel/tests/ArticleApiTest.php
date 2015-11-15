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
        'descriptionText' => '',
        'isDraft' => 1, // why not true?
        'authorUserIds' => [1],
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
      
    $this->get('/api/article/2')
      ->assertResponseOk();
    $this->seeJson([
      'id' => 3,
      'title' => 'article2',
      'descriptionText' => 'un bel article, celui-ci',
      'isDraft' => 0,
      'authorUserIds' => [1, 2],
      ]);
  }
}
