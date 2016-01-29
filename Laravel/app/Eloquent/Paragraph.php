<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Paragraph extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'paragraphs';

  protected $fillable = ['title', 'article_id', 'block_content_id', 'block_content_type', 'hublotContentId', 'hublotContentType', 'place_id', 'date', 'weather', 'authorId', 'isDraft'];

  protected $visible = ['id', 'title', 'blockContent', 'block_content_type', 'place', 'date', 'weather', 'isDraft'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'id' => 'unique:paragraphs,id',
    'title' => 'required|min:3|max:200',
    'weather' => 'min:3|max:20',
    'date' => 'min:3|max:10',
    'block_content_type' => 'min:3|max:30|required_with:block_content',
    'block_content' => 'required_with:block_content_type',
  ];

  static $default_fillable_values = [
    'title' => '',
    'weather' => '',
    'date' => '',
    'isDraft' => true,
    'authorId' => -1,
  ];

  public function place() {
    return $this->belongsTo('Jetlag\Eloquent\Place');
  }

  public function blockContent() {
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
        if (array_key_exists('id', $subRequest['block_content']))
        {
          $picture = Picture::find($subRequest['block_content']['id']);
        } else
        {
          $picture = new Picture;
        }
        $picture->extract($subRequest['block_content']);
        $this->blockContent()->associate($picture);
      } else if ('Jetlag\Eloquent\TextContent' == $subRequest['block_content_type'])
      {
        if (array_key_exists('id', $subRequest['block_content']))
        {
          $text = TextContent::find($subRequest['block_content']['id']);
        } else
        {
          $text = new TextContent;
          $text->content = '';
        }
        $text->content = array_key_exists('content', $subRequest['block_content']) ? $subRequest['block_content']['content'] : $text->content;
        $text->authorId = -1;
        $text->save();
        $this->blockContent()->associate($text);
      } else if ('Jetlag\Eloquent\Map' == $subRequest['block_content_type'])
      {
        if (array_key_exists('id', $subRequest['block_content']))
        {
          $map = Map::find($subRequest['block_content']['id']);
        } else
        {
          $map = new Map;
        }
        $map->extract($subRequest['block_content']);
        $this->blockContent()->associate($map);
      }
    }

    if (array_key_exists('place', $subRequest))
    {
      $place = Place::create(array_merge(Place::$default_fillable_values, $subRequest['place']));
      $this->place()->associate($place);
    }
    $this->save();
    return $this;
  }
}
