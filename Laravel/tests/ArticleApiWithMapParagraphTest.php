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

    Log::debug(" expecting author_id=4 and user_id=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => "article with id 2",
      'description_text' => 'this is a cool article isnt it? id 2',
      'is_draft' => 1, // why not true?
      // 'author_users' => [$writer->id => 'writer'],
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
                'id' => $marker1->id,
                'description' => $marker1->description,
                'place' => [
                  'description' => $places[0]->description,
                  'latitude' => $places[0]->latitude,
                  'longitude' => $places[0]->longitude,
                  'altitude' => $places[0]->altitude,
                ],
              ],
              [
                'id' => $marker2->id,
                'description' => $marker2->description,
                'place' => [
                  'description' => $places[1]->description,
                  'latitude' => $places[1]->latitude,
                  'longitude' => $places[1]->longitude,
                  'altitude' => $places[1]->altitude,
                ],
              ],
            ],
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'is_draft' => 1,
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

  public function testApiStoreArticleWithMapParagraph()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            'caption' => 'This is a pretty cool map, that is for sure',
            "markers" => [
              [
                "description" => "La tour eiffel ici",
                "place" => [
                  "altitude" => 212,
                  "latitude" => 45.76388,
                  "description" => "La Tour",
                  "longitude" => 4.82244,
                ]
              ],
              [
                "description" => "Le bout du monde, c cool",
                "place" => [
                  "altitude" => 14,
                  "latitude" => 48.75107,
                  "description" => "au bout du Cap, à Forillon",
                  "longitude" => -64.16094,
                ]
              ]
            ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'place' => [
            'latitude' => 73.43,
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
      'description_text' => '',
      'is_draft' => 1,
      'description_picture' => null,
      'paragraphs' => [
        [
          'id' => 1,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            "id" => 1,
            "caption" => "This is a pretty cool map, that is for sure",
            "markers" => [
              [
                "id" => 1,
                "description" => "La tour eiffel ici",
                "place" => [
                  "altitude" => 212,
                  "latitude" => 45.76388,
                  "description" => "La Tour",
                  "longitude" => 4.82244,
                ]
              ],
              [
                "id" => 2,
                "description" => "Le bout du monde, c cool",
                "place" => [
                  "altitude" => 14,
                  "latitude" => 48.75107,
                  "description" => "au bout du Cap, à Forillon",
                  "longitude" => -64.16094,
                ]
              ]
            ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'is_draft' => 1,
          'place' => [
            'latitude' => 73.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
        ]
      ],
    ]);
  }

  public function testApiUpdateArticleWithMapParagraph()
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

    Log::debug(" expecting author_id=4 and user_id=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
    ->put($this->articleApiUrl . $article->id, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            'id' => $map->id,
            'caption' => 'This is a pretty cool map, that is for sure',
            "markers" => [
              [
                'id' => $marker1->id,
                "description" => "La tour eiffel ici",
                "place" => [
                  "altitude" => 212,
                  "latitude" => 45.76388,
                  "description" => "La Tour",
                  "longitude" => 4.82244,
                ]
              ],
              [
                'id' => $marker2->id,
                "description" => "Le bout du monde, c cool",
                "place" => [
                  "altitude" => 14,
                  "latitude" => 48.75107,
                  "description" => "au bout du Cap, à Forillon",
                  "longitude" => -64.16094,
                ]
              ]
            ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'place' => [
            'latitude' => 73.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
        ]
      ],
    ], ['ContentType' => 'application/json'])
    ->assertResponseStatus(200);
    $this->seeJson([
      'id' => $article->id,
    ]);

    $this->actingAs($writer)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => 'article1',
      'description_text' => $article->description_text,
      'is_draft' => 1,
      'description_picture' => null,
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            "id" => $map->id,
            "caption" => "This is a pretty cool map, that is for sure",
            "markers" => [
              [
                "id" => $marker1->id,
                "description" => "La tour eiffel ici",
                "place" => [
                  "altitude" => 212,
                  "latitude" => 45.76388,
                  "description" => "La Tour",
                  "longitude" => 4.82244,
                ]
              ],
              [
                "id" => $marker2->id,
                "description" => "Le bout du monde, c cool",
                "place" => [
                  "altitude" => 14,
                  "latitude" => 48.75107,
                  "description" => "au bout du Cap, à Forillon",
                  "longitude" => -64.16094,
                ]
              ]
            ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'is_draft' => 1,
          'place' => [
            'latitude' => 73.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
        ]
      ],
    ]);
  }

  public function testApiPartialUpdateArticleWithMapParagraph()
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

    Log::debug(" expecting author_id=4 and user_id=" . $writer->id . " and role=writer for article " . $article->id);
    $this->actingAs($writer)
    ->put($this->articleApiUrl . $article->id, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            'id' => $map->id,
            "markers" => [
              [
                'id' => $marker1->id,
                "description" => "La tour eiffel ici",
                "place" => [
                  "latitude" => 45.76388,
                  "description" => "La Tour",
                  "longitude" => 4.82244,
                ]
              ],
              [
                "description" => "Le bout du monde, c cool",
                "place" => [
                  "altitude" => 14,
                  "latitude" => 48.75107,
                  "description" => "au bout du Cap, à Forillon",
                  "longitude" => -64.16094,
                ]
              ]
            ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'place' => [
            'latitude' => 73.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
        ]
      ],
    ], ['ContentType' => 'application/json'])
    ->assertResponseStatus(200);
    $this->seeJson([
      'id' => $article->id,
    ]);

    $this->actingAs($writer)
    ->get($this->articleApiUrl . $article->id)
    ->assertResponseOk();
    $this->seeJson([
      'id' => $article->id,
      'title' => 'article1',
      'description_text' => $article->description_text,
      'is_draft' => 1,
      'description_picture' => null,
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => $paragraph->title,
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            "id" => $map->id,
            "caption" => $map->caption,
            "markers" => [
              [
                "id" => $marker1->id,
                "description" => "La tour eiffel ici",
                "place" => [
                  "altitude" => $places[0]->altitude,
                  "latitude" => 45.76388,
                  "description" => "La Tour",
                  "longitude" => 4.82244,
                ]
              ],
              [
                'id' => $marker2->id,
                'description' => $marker2->description,
                'place' => [
                  'description' => $places[1]->description,
                  'latitude' => $places[1]->latitude,
                  'longitude' => $places[1]->longitude,
                  'altitude' => $places[1]->altitude,
                ],
              ],
              [
                "id" => 3,
                "description" => "Le bout du monde, c cool",
                "place" => [
                  "altitude" => 14,
                  "latitude" => 48.75107,
                  "description" => "au bout du Cap, à Forillon",
                  "longitude" => -64.16094,
                ]
              ]
            ]
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'is_draft' => 1,
          'place' => [
            'latitude' => 73.43,
            'longitude' => -43.57,
            'altitude' => -156.9,
            'description' => 'lala sous mer',
          ],
        ]
      ],
    ]);
  }

}
