<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class ArticleApiWithMistakenParagraphTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
  //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiStoreArticleWithMapParagraphWithIgnoredExtraData()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [
      'title' => 'article1',
      'extraArticleKey' => 'extraArticleValue',
      'paragraphs' => [
        [
          'extraKey' => 'extraValue',
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            'extraMapKey' => 'extraMapValue',
            'caption' => 'This is a pretty cool map, that is for sure',
            "markers" => [
              [
                'extraMarkerKey' => 'extraMarkerValue',
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
                'extraMapKey' => 'extraMapValue',
                "place" => [
                  'extraPlaceKey' => 'extraPlaceValue',
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
      'descriptionText' => '',
      'is_draft' => 1,
      'descriptionMedia' => [],
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

  public function testApiStoreArticleWithMapParagraphWithFailingMissingContentType()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'title' => 'A first paragraph',
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
    ->assertResponseStatus(400);
  }

  public function testApiStoreArticleWithMapParagraphOnlyWithRequiredData()
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
            "markers" => [
              [
                "place" => [
                ]
              ],
              [
                "place" => [
                ]
              ]
            ]
          ],
          'place' => [
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
      'is_draft' => 1,
      'descriptionMedia' => [],
      'paragraphs' => [
        [
          'id' => 1,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            "id" => 1,
            "caption" => "",
            "markers" => [
              [
                "id" => 1,
                "description" => "",
                "place" => [
                  "altitude" => 0,
                  "latitude" => -200,
                  "description" => "",
                  "longitude" => -200,
                ]
              ],
              [
                "id" => 2,
                "description" => "",
                "place" => [
                  "altitude" => 0,
                  "latitude" => -200,
                  "description" => "",
                  "longitude" => -200,
                ]
              ]
            ]
          ],
          'weather' => '',
          'date' => '0000-00-00',
          'is_draft' => 1,
          'place' => [
            "altitude" => 0,
            "latitude" => -200,
            "description" => "",
            "longitude" => -200,
          ],
        ]
      ],
    ]);
  }

  public function testApiCannotStoreArticleWithMapParagraphWithId()
  {
    $user = factory(Jetlag\User::class)->create();
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create();

    $this->actingAs($user)
    ->post($this->articleApiUrl, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => 1,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Map',
          'block_content' => [
            "markers" => [
              [
                "place" => [
                ]
              ],
              [
                "place" => [
                ]
              ]
            ]
          ],
          'place' => [
          ],
        ]
      ],
    ], ['ContentType' => 'application/json'])
    ->assertResponseStatus(400);
  }

}
