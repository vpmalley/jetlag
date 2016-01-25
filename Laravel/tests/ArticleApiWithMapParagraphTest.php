<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class ArticleApiWithMapParagraphTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
    //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiGetArticleWithMapParagraph()
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
    $map = factory(Jetlag\Eloquent\Map::class)->create();
    $places = factory(Jetlag\Eloquent\Place::class, 2)->create();
    $marker1 = factory(Jetlag\Eloquent\Marker::class)->create([
      'author_id' => $authorId,
      'map_id' => $map->id,
      'place_id' => $places[0]->id,
    ]);
    $marker2 = factory(Jetlag\Eloquent\Marker::class)->create([
      'author_id' => $authorId,
      'map_id' => $map->id,
      'place_id' => $places[1]->id,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'block_content_id' => $map->id,
      'block_content_type' => 'Jetlag\Eloquent\Map',
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
            'block_content_type' => 'Jetlag\Eloquent\Map',
            'block_content' => [
              'id' => $map->id,
              'caption' => $map->caption,
              'markers' => [
                [
                  'description' => $marker1->description,
                  'place' => [
                    'localisation' => $places[0]->localisation,
                    'latitude' => $places[0]->latitude,
                    'longitude' => $places[0]->longitude,
                    'altitude' => $places[0]->altitude,
                  ],
                ],
                [
                  'description' => $marker2->description,
                  'place' => [
                    'localisation' => $places[1]->localisation,
                    'latitude' => $places[1]->latitude,
                    'longitude' => $places[1]->longitude,
                    'altitude' => $places[1]->altitude,
                  ],
                ],
              ],
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
}
