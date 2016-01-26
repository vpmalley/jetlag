<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;
use Jetlag\Eloquent\TextContent;

class ArticleApiWithPictureParagraphTest extends TestCase {

  use WithoutMiddleware; // note: as we bypass middleware (in particular auth), we expect 403 instead of 401
    //(i.e. non-logged user is forbidden to access resources requiring login)
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/articles/";

  public function testApiGetArticleWithPictureParagraph()
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
      'block_content_id' => $picture->id,
      'block_content_type' => 'Jetlag\Eloquent\Picture',
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
            'block_content_type' => 'Jetlag\Eloquent\Picture',
            'block_content' => [
              'id' => $picture->id,
              'small_url' => [ 'caption' => $links[0]->caption, 'url' => $links[0]->url ],
              'medium_url' => [ 'caption' => $links[1]->caption, 'url' => $links[1]->url ],
              'big_url' => [ 'caption' => $links[2]->caption, 'url' => $links[2]->url ],
              'place' => [
                'description' => $places[0]->description,
                'latitude' => $places[0]->latitude,
                'longitude' => $places[0]->longitude,
                'altitude' => $places[0]->altitude,
              ]
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

  public function testApiStoreArticleWithPictureParagraph()
  {
    $user = factory(Jetlag\User::class)->create();

    $this->actingAs($user)
      ->post($this->articleApiUrl, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Picture',
          'block_content' => [
            'big_url' => [
                'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
              ],
            'place' => [
              'latitude' => 63.7852,
              'longitude' => 94.3302,
            ],
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'place' => [
            'latitude' => 123.43,
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
        'block_content_type' => 'Jetlag\Eloquent\Picture',
        'block_content_type' => 'Jetlag\Eloquent\Picture',
        'block_content' => [
          'id' => 1,
          'small_url' => null,
          'medium_url' => null,
          'big_url' => [
            'caption' => '',
            'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
          ],
          'place' => [
            'latitude' => 63.7852,
            'longitude' => 94.3302,
            'altitude' => 0,
            'description' => '',
          ],
        ],
        'weather' => 'cloudy',
        'date' => '2016-01-03',
        'isDraft' => 1,
        'place' => [
          'latitude' => 123.43,
          'longitude' => -43.57,
          'altitude' => -156.9,
          'description' => 'lala sous mer',
        ],
      ]
    ],
    ]);
  }

  public function testApiUpdateArticleWithPictureParagraph()
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
      'descriptionText' => 'this is a cool article isnt it? id 2',
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
      'article_id' => null,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'block_content_id' => $picture->id,
      'block_content_type' => 'Jetlag\Eloquent\Picture',
      'place_id' => $places[1]->id,
    ]);

    $this->actingAs($writer)
      ->put($this->articleApiUrl . $article->id, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Picture',
          'block_content' => [
            'id' => $picture->id,
            'big_url' => [
                'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
              ],
            'place' => [
              'latitude' => 63.7852,
              'longitude' => 94.3302,
            ],
          ],
          'weather' => 'sunny',
          'date' => '2013-12-12',
          'place' => [
            'latitude' => 123.43,
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
    'title' => 'article1',
    'descriptionText' => 'this is a cool article isnt it? id 2',
    'isDraft' => 1,
    'descriptionMedia' => [],
    'paragraphs' => [
      [
        'id' => $paragraph->id,
        'title' => 'A first paragraph',
        'block_content_type' => 'Jetlag\Eloquent\Picture',
        'block_content' => [
          'id' => $picture->id,
          'small_url' => [
            'caption' => $links[0]->caption,
            'url' => $links[0]->url,
          ],
          'medium_url' => [
            'caption' => $links[1]->caption,
            'url' => $links[1]->url,
          ],
          'big_url' => [
            'caption' => $links[2]->caption,
            'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
          ],
          'place' => [
            'latitude' => 63.7852,
            'longitude' => 94.3302,
            'altitude' => $places[0]->altitude,
            'description' => $places[0]->description,
          ],
        ],
        'weather' => 'sunny',
        'date' => '2013-12-12',
        'isDraft' => 1,
        'place' => [
          'latitude' => 123.43,
          'longitude' => -43.57,
          'altitude' => -156.9,
          'description' => 'lala sous mer',
        ],
      ]
    ],
    ]);
  }

  public function testApiPartialUpdateArticleWithPictureParagraph()
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
      'descriptionText' => 'this is a cool article isnt it? id 2',
    ]);
    $link = factory(Jetlag\Eloquent\Link::class, 'web')->create([
      'authorId' => $authorId,
    ]);
    $place = factory(Jetlag\Eloquent\Place::class)->create();
    $picture = factory(Jetlag\Eloquent\Picture::class)->create([
      'authorId' => $authorId,
      'smallPictureLink_id' => $link->id,
      'place_id' => $place->id,
      'article_id' => null,
    ]);
    $paragraph = factory(Jetlag\Eloquent\Paragraph::class)->create([
      'title' => 'A first paragraph',
      'weather' => 'cloudy',
      'date' => '2016-01-03',
      'article_id' => $article->id,
      'block_content_id' => $picture->id,
      'block_content_type' => 'Jetlag\Eloquent\Picture',
    ]);

    $this->actingAs($writer)
      ->put($this->articleApiUrl . $article->id, [
      'title' => 'article1',
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Picture',
          'block_content' => [
            'id' => $picture->id,
            'medium_url' => [
                'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
              ],
          ],
          'place' => [
            'latitude' => 63.7852,
            'longitude' => 94.3302,
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
      'title' => 'article1',
      'descriptionText' => 'this is a cool article isnt it? id 2',
      'isDraft' => 1,
      'descriptionMedia' => [],
      'paragraphs' => [
        [
          'id' => $paragraph->id,
          'title' => 'A first paragraph',
          'block_content_type' => 'Jetlag\Eloquent\Picture',
          'block_content' => [
            'id' => $picture->id,
            'small_url' => [
              'caption' => $link->caption,
              'url' => $link->url,
            ],
            'medium_url' => [
              'caption' => '',
              'url' => 'http://s2.lemde.fr/image2x/2015/11/15/92x61/4810325_7_5d59_mauri7-rue-du-faubourg-saint-denis-10e_86775f5ea996250791714e43e8058b07.jpg',
            ],
            'big_url' => null,
            'place' => [
              'latitude' => $place->latitude,
              'longitude' => $place->longitude,
              'altitude' => $place->altitude,
              'description' => $place->description,
            ],
          ],
          'weather' => 'cloudy',
          'date' => '2016-01-03',
          'isDraft' => 1,
          'place' => [
            'latitude' => 63.7852,
            'longitude' => 94.3302,
            'altitude' => 0,
            'description' => '',
          ],
        ]
      ],
    ]);
  }
}
