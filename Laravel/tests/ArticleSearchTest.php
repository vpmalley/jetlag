<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Jetlag\User;

class ArticleSearchTest extends TestCase {

  use WithoutMiddleware;
  use DatabaseMigrations;

  protected $baseUrl = "http://homestead.app";
  protected $articleApiUrl = "/api/0.1/search/articles?query=";

    public function testSearchArticlesAsWriter()
    {
      $authorId = 3;
      $writer = factory(Jetlag\User::class)->create();
      factory(Jetlag\Eloquent\Author::class, 'writer')->create([
        'author_id' => $authorId,
        'user_id' => $writer->id
      ]);

      $writtenArticle = factory(Jetlag\Eloquent\Article::class)->create([
        'author_id' => $authorId,
        'title' => 'article in Asia',
        'description_text' => 'this is a cool article isnt it?',
        'is_draft' => false,
      ]);
      $privateArticle = factory(Jetlag\Eloquent\Article::class)->create([
        'author_id' => 123,
        'title' => 'Where is the best evaa? Asia is the best evaa',
        'description_text' => 'this is a cool article isnt it?',
        'is_public' => false,
        'is_draft' => false,
      ]);

      Log::debug(" expecting only an article written by the user");
      $this->actingAs($writer)
      ->get($this->articleApiUrl . 'asia')
      ->assertResponseOk();
      $this->seeJson([
        'id' => $writtenArticle->id,
        'url' => $this->baseUrl . "/article/" . $writtenArticle->id,
        'title' => $writtenArticle->title,
        'description_text' => $writtenArticle->description_text,
        'author_users' => [$writer->id => 'writer'],
        'description_picture' => null,
        'is_draft' => false,
        'is_public' => $writtenArticle->is_public,
        'paragraphs' => [],
      ]);
    }

    public function testSearchPublicArticle()
    {
      $publicArticle = factory(Jetlag\Eloquent\Article::class)->create([
        'author_id' => 123,
        'title' => 'Asia tour',
        'description_text' => 'this is a cool article isnt it?',
        'is_public' => true,
        'is_draft' => false,
      ]);
      $privateArticle = factory(Jetlag\Eloquent\Article::class)->create([
        'author_id' => 123,
        'title' => 'Where is the best evaa? Asia is the best evaa',
        'description_text' => 'this is a cool article isnt it?',
        'is_public' => false,
        'is_draft' => false,
      ]);

      Log::debug("expecting only a public article");
      $this
      ->get($this->articleApiUrl . 'asia')
      ->assertResponseOk();
      $this->seeJson([
        'id' => $publicArticle->id,
        'url' => $this->baseUrl . "/article/" . $publicArticle->id,
        'title' => $publicArticle->title,
        'description_text' => $publicArticle->description_text,
        'author_users' => [],
        'description_picture' => null,
        'is_draft' => false,
        'is_public' => true,
        'paragraphs' => [],
      ]);
    }

    public function testSearchNonDraftArticle()
    {
      $publishedArticle = factory(Jetlag\Eloquent\Article::class)->create([
        'author_id' => 123,
        'title' => 'Asia tour',
        'description_text' => 'this is a cool article isnt it?',
        'is_public' => true,
        'is_draft' => false,
      ]);
      $draftArticle = factory(Jetlag\Eloquent\Article::class)->create([
        'author_id' => 123,
        'title' => 'Where is the best evaa? Asia is the best evaa',
        'description_text' => 'this is a cool article isnt it?',
        'is_public' => true,
        'is_draft' => true,
      ]);

      Log::debug("expecting only a public article");
      $this
      ->get($this->articleApiUrl . 'asia')
      ->assertResponseOk();
      $this->seeJson([
        'id' => $publishedArticle->id,
        'url' => $this->baseUrl . "/article/" . $publishedArticle->id,
        'title' => $publishedArticle->title,
        'description_text' => $publishedArticle->description_text,
        'author_users' => [],
        'description_picture' => null,
        'is_draft' => false,
        'is_public' => true,
        'paragraphs' => [],
      ]);
    }
  }
