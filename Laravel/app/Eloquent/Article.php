<?php

namespace Jetlag\Eloquent;

use Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use TeamTNT\TNTSearch\TNTSearch;

class Article extends Model
{
  use SoftDeletes;

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'articles';

  /**
  * The attributes that are mass assignable.
  * title is the title of the article
  * description_text is a text description of the article
  * is_draft is a boolean, to indicate whether the article is a draft
  *
  * @var array
  */
  public $fillable = ['id', 'title', 'description_text', 'is_draft', 'is_public'];

  protected $visible = ['id', 'title', 'description_text', 'is_draft', 'is_public', 'description_picture', 'paragraphs', 'url'];

  protected $casts = [
      'is_draft' => 'boolean',
      'is_public' => 'boolean',
  ];

  /**
   * The rules for validating input
   */
  static $creationRules = [
    'title' => 'max:200',
    'description_text' => 'max:500',
    'descriptionMediaUrl' => 'max:200',
    'is_draft' => 'boolean',
    'is_public' => 'boolean',
  ];

  /**
   * The rules for validating input
   */
  static $updateRules = [
    'title' => 'required|min:3|max:200',
    'description_text' => 'max:500',
    'descriptionMediaUrl' => 'max:200',
    'is_draft' => 'boolean',
    'is_public' => 'boolean',
  ];

  static $default_fillable_values = [
    'title' => '',
    'description_text' => '',
    'date' => '',
    'is_draft' => true,
    'is_public' => false,
  ];

  static $relationsToLoad = ['description_picture', 'paragraphs'];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  public function description_picture()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture');
  }

  public function paragraphs()
  {
    return $this->hasMany('Jetlag\Eloquent\Paragraph');
  }

  // -- Extraction

  /**
  * Extracts the picture from the subrequest
  *
  * @param  array  $subRequest
  * @return  Jetlag\Eloquent\Picture the extracted picture
  */
  public function extract($subRequest)
  {
    if (array_key_exists('title', $subRequest)) {
        $this->title = $subRequest['title'];
    }
    if (array_key_exists('description_text', $subRequest)) {
        $this->description_text = $subRequest['description_text'];
    }
    if (array_key_exists('is_draft', $subRequest)) {
        $this->is_draft = $subRequest['is_draft'];
    }
    if (array_key_exists('is_public', $subRequest)) {
        $this->is_public = $subRequest['is_public'];
    }

    $this->save();

    $this->extractParagraphs($subRequest);
    $this->extractDescriptionPicture($subRequest);

    return $this;
  }

  /**
  * Extracts the picture from the subrequest
  *
  * @param  array  $subRequest
  * @return  Jetlag\Eloquent\Picture the extracted picture
  */
  public function extractDescriptionPicture($subRequest)
  {
    if (array_key_exists('description_picture', $subRequest)) {
      $pictureSubRequest = $subRequest['description_picture'];
      $validator = Validator::make($pictureSubRequest, Picture::$rules);
      if ($validator->fails()) {
        abort(400, $validator->errors());
      }
      if (array_key_exists('id', $pictureSubRequest)) {
        $picture = Picture::find($pictureSubRequest['id']);
      } else {
        $picture = new Picture;
      }
      $picture->extract($pictureSubRequest);
      $this->description_picture()->save($picture);
    }

  }

  /**
  * Extracts the paragraphs from the subrequest
  *
  * @param  array  $subRequest
  * @return  Jetlag\Eloquent\Paragraph the extracted picture
  */
  public function extractParagraphs($subRequest)
  {
    if (array_key_exists('paragraphs', $subRequest)) {
      foreach ($subRequest['paragraphs'] as $paragraphSubRequest) {
        $validator = Validator::make($paragraphSubRequest, Paragraph::$rules);
        if ($validator->fails()) {
          abort(400, $validator->errors());
        }
        if (array_key_exists('id', $paragraphSubRequest)) {
          $paragraph = Paragraph::find($paragraphSubRequest['id']);
        } else {
          $paragraphSubRequest = array_merge(Paragraph::$default_fillable_values, $paragraphSubRequest);
          $paragraph = Paragraph::create($paragraphSubRequest);
        }
        $paragraph->extract($paragraphSubRequest);
        $this->paragraphs()->save($paragraph);
      }
    }
    return $this->paragraphs;
  }

  // -- Loading relations

  public function loadRelations() {
    $this->load(Article::$relationsToLoad);
    if ($this->description_picture) {
      $this->description_picture->loadRelations();
    }
    if ($this->paragraphs) {
      foreach ($this->paragraphs as $paragraph) {
        $paragraph->loadRelations();
      }
    }
  }

  public function addUrl() {
    $this->url = url('/article/' . $this->id);
  }

  // -- Indexing

  public static function boot()
  {
      Article::created([__CLASS__, 'insertToIndex']);
      Article::updated([__CLASS__, 'updateIndex']);
      Article::deleted([__CLASS__, 'deleteFromIndex']);
  }

  public static function insertToIndex($article)
  {
      $index = Article::getIndex();
      $index->insert($article->toArray());
  }

  public static function deleteFromIndex($article)
  {
      $index = Article::getIndex();
      $index->delete($article->id);
  }

  public static function updateIndex($article)
  {
      $index = Article::getIndex();
      $index->update($article->id, $article->toArray());
  }

  private static function getIndex()
  {
    $tnt = new TNTSearch;
    $config = [
      'driver'    => 'mysql',
      'host'      => getenv('DB_HOST'),
      'database'  => getenv('DB_DATABASE'),
      'username'  => getenv('DB_USERNAME'),
      'password'  => getenv('DB_PASSWORD'),
      'storage'   => env('SEARCH_INDEX_LOC', storage_path())
    ];
    $tnt->loadConfig($config);
    $tnt->selectIndex("articles.index");
    return $tnt->getIndex();
  }

}
