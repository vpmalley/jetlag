<?php

namespace Jetlag\Eloquent;

use Validator;
use Illuminate\Database\Eloquent\Model;

class Paragraph extends Model
{
  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'paragraphs';

  protected $fillable = ['id', 'title', 'block_content_type', 'date', 'weather', 'is_draft'];

  protected $visible = ['id', 'title', 'block_content', 'block_content_type', 'place', 'date', 'weather', 'is_draft'];

  /**
  * The rules for validating input
  */
  static $rules = [
    'id' => 'numeric',
    'title' => 'string|min:3|max:200',
    'weather' => 'string|min:3|max:20',
    'date' => 'date',
    'block_content_type' => 'string|min:3|max:30|required_with:block_content',
    'block_content' => 'required_with:block_content_type',
  ];

  static $default_fillable_values = [
    'title' => '',
    'weather' => '',
    'date' => '',
    'is_draft' => true,
    'author_id' => -1,
  ];

  static $relationsToLoad = ['block_content', 'place'];

  public function place() {
    return $this->belongsTo('Jetlag\Eloquent\Place');
  }

  public function block_content() {
    return $this->morphTo();
  }

  /**
  * Extracts the paragraph from the subrequest
  *
  * @param  array  $subRequest
  * @return  Jetlag\Eloquent\Paragraph the extracted paragraph
  */
  public function extract($subRequest)
  {
    if (array_key_exists('block_content', $subRequest) && array_key_exists('block_content_type', $subRequest))
    {
      if ('Jetlag\Eloquent\Picture' == $subRequest['block_content_type'])
      {
        $this->extractAndBindPicture($subRequest['block_content']);
      } else if ('Jetlag\Eloquent\TextContent' == $subRequest['block_content_type'])
      {
        $this->extractAndBindText($subRequest['block_content']);

      } else if ('Jetlag\Eloquent\Map' == $subRequest['block_content_type'])
      {
        $this->extractAndBindMap($subRequest['block_content']);
      }
    }

    if (array_key_exists('place', $subRequest))
    {
      $validator = Validator::make($subRequest['place'], Place::$rules);
      if ($validator->fails()) {
        abort(400, $validator->errors());
      }
      $place = Place::create(array_merge(Place::$default_fillable_values, $subRequest['place']));
      $this->place()->associate($place);
    }
    if (array_key_exists('date', $subRequest)) {
        $this->date = $subRequest['date'];
    }
    if (array_key_exists('weather', $subRequest)) {
        $this->weather = $subRequest['weather'];
    }
    $this->save();
    return $this;
  }

  public function extractAndBindPicture($pictureSubRequest)
  {
    $validator = Validator::make($pictureSubRequest, Picture::$rules);
    if ($validator->fails()) {
      abort(400, $validator->errors());
    }
    if (array_key_exists('id', $pictureSubRequest))
    {
      $picture = Picture::find($pictureSubRequest['id']);
    } else
    {
      $picture = new Picture;
    }
    $picture->extract($pictureSubRequest);
    $this->block_content()->associate($picture);
  }

  public function extractAndBindText($textSubRequest)
  {
    $validator = Validator::make($textSubRequest, TextContent::$rules);
    if ($validator->fails()) {
      abort(400, $validator->errors());
    }
    if (array_key_exists('id', $textSubRequest))
    {
      $text = TextContent::find($textSubRequest['id']);
    } else
    {
      $text = new TextContent;
      $text->content = '';
    }
    $text->content = array_key_exists('content', $textSubRequest) ? $textSubRequest['content'] : $text->content;
    $text->author_id = -1;
    $text->save();
    $this->block_content()->associate($text);
  }

  public function extractAndBindMap($mapSubRequest)
  {
    $validator = Validator::make($mapSubRequest, Map::$rules);
    if ($validator->fails()) {
      abort(400, $validator->errors());
    }
    if (array_key_exists('id', $mapSubRequest))
    {
      $map = Map::find($mapSubRequest['id']);
    } else
    {
      $map = new Map;
    }
    $map->extract($mapSubRequest);
    $this->block_content()->associate($map);
  }

  // -- Loading relations

  public function loadRelations() {
    $this->load(Paragraph::$relationsToLoad);
    if ($this->block_content && ('Jetlag\Eloquent\Picture' == $this->block_content_type || 'Jetlag\Eloquent\Map' == $this->block_content_type)) {
      $this->block_content->loadRelations();
    }
  }
}
