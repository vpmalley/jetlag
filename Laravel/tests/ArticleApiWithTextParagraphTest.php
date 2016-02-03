<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class ArticleApiWithTextParagraphTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
    //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiGetArticleWithTextParagraph()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2'
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'author_id' => $authorId,
    ]);
    $places = factory(Jetlag\Eloquent\Place::class, 2)->create();
    $text = factory(Jetlag\Eloquent\TextContent::class)->create([
      'author_id' => $authorId,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'block_content_id' => $text->id,
      'block_content_type' => 'Jetlag\Eloquent\TextContent',
      'place_id' => $places[1]->id,
    ]);

    Log::debug(" expecting author_id=4 and userId=" . $writer->id . " and role=writer for article " . $article->id);
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
            'block_content_type' => 'Jetlag\Eloquent\TextContent',
            'block_content' => [
              'id' => $text->id,
              'content' => $text->content,
            ],
            'weather' => 'cloudy',
            'date' => '2016-01-03',
            'isDraft' => 1,
            'place' => [
              'description' => $places[1]->description,
              'latitude' => $places[1]->latitude,
              'longitude' => $places[1]->longitude,
              'altitude' => $places[1]->altitude,
            ]
          ]
        ],
      ]);
  }

  public function testApiStoreArticleWithTextParagraph()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
      ->post($this->articleApiUrl, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\TextContent',
          'block_content' => [
            'content' => 'This is a long story, that is for sure',
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'place' => [
            'latitude' => 83.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
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
        'block_content_type' => 'Jetlag\Eloquent\TextContent',
        'block_content' => [
          'id' => 1,
          'content' => 'This is a long story, that is for sure',
        ],
        'weather' => 'cloudy',
        'date' => '2016-01-03',
        'isDraft' => 1,
        'place' => [
          'latitude' => 83.43,
          'longitude' => -43.57,
          'altitude' => -156.9,
          'description' => 'lala sous mer',
        ],
      ]
    ],
    ]);
  }

  public function testApiUpdateArticleWithTextParagraph()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
    ]);
    $links = factory(Jetlag\Eloquent\Link::class, 'web', 3)->create([
      'author_id' => $authorId,
    ]);
    $places = factory(Jetlag\Eloquent\Place::class, 2)->create();
    $text = factory(Jetlag\Eloquent\TextContent::class)->create([
      'author_id' => $authorId,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'block_content_id' => $text->id,
      'block_content_type' => 'Jetlag\Eloquent\TextContent',
      'place_id' => $places[1]->id,
    ]);

    $this->actingAs($writer)
      ->put($this->articleApiUrl . $article->id, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\TextContent',
          'block_content' => [
            'id' => 1,
            'content' => 'This is a long story, that is for sure',
          ],
          'weather' => 'sunny',
          'date' => '2013-12-12',
          'place' => [
            'latitude' => 83.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
        ]
      ],
    ], ['ContentType' => 'application/json'])
      ->assertResponseStatus(200);
    $this->seeJson([
        'id' => 1,
      ]);

    $this->actingAs($writer)
      ->get($this->articleApiUrl . 1)
      ->assertResponseOk();
    $this->seeJson([
    'title' => 'article1',
    'descriptionText' => 'this is a cool article isnt it? id 2',
    'isDraft' => 1,
    'descriptionMedia' => [],
    'paragraphs' => [
      [
        'id' => $paragraph->id,
        'title' => 'A first paragraph',
        'block_content_type' => 'Jetlag\Eloquent\TextContent',
        'block_content' => [
          'id' => 1,
          'content' => 'This is a long story, that is for sure',
        ],
        'weather' => 'sunny',
        'date' => '2013-12-12',
        'isDraft' => 1,
        'place' => [
          'latitude' => 83.43,
          'longitude' => -43.57,
          'altitude' => -156.9,
          'description' => 'lala sous mer',
        ],
      ]
    ],
    ]);
  }

  public function testApiPartialUpdateArticleWithTextParagraph()
  {
    $authorId = 6;
    $writer = factory(Jetlag\User::class)->create();
    factory(Jetlag\Eloquent\Author::class, 'writer')->create([
      'author_id' => $authorId,
      'userId' => $writer->id
    ]);
    $article = factory(Jetlag\Eloquent\Article::class)->create([
      'author_id' => $authorId,
      'title' => "article with id 2",
      'descriptionText' => 'this is a cool article isnt it? id 2',
    ]);
    $link = factory(Jetlag\Eloquent\Link::class, 'web')->create([
      'author_id' => $authorId,
    ]);
    $place = factory(Jetlag\Eloquent\Place::class)->create();
    $text = factory(Jetlag\Eloquent\TextContent::class)->create([
      'author_id' => $authorId,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'block_content_id' => $text->id,
      'block_content_type' => 'Jetlag\Eloquent\TextContent',
    ]);

    $this->actingAs($writer)
      ->put($this->articleApiUrl . $article->id, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\TextContent',
          'block_content' => [
            'id' => 1,
            'content' => 'This is a long story, that is for sure',
          ],
          'place' => [
            'latitude' => 63.7852,
          ],
        ]
      ],
    ], ['ContentType' => 'application/json'])
      ->assertResponseStatus(200);
    $this->seeJson([
        'id' => 1,
      ]);

    $this->actingAs($writer)
      ->get($this->articleApiUrl . 1)
      ->assertResponseOk();
    $this->seeJson([
    'title' => 'article1',
    'descriptionText' => 'this is a cool article isnt it? id 2',
    'isDraft' => 1,
    'descriptionMedia' => [],
    'paragraphs' => [
      [
        'id' => $paragraph->id,
        'title' => 'A first paragraph',
        'block_content_type' => 'Jetlag\Eloquent\TextContent',
        'block_content' => [
          'id' => 1,
          'content' => 'This is a long story, that is for sure',
        ],
        'weather' => 'cloudy',
        'date' => '2016-01-03',
        'isDraft' => 1,
        'place' => [
          'latitude' => 63.7852,
          'longitude' => -200,
          'altitude' => 0,
          'description' => '',
        ],
      ]
    ],
    ]);
  }
}
