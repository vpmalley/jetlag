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

  protected $fillable = ['title', 'article_id', 'blockContentId', 'blockContentType', 'hublotContentId', 'hublotContentType', 'place_id', 'date', 'weather', 'authorId', 'isDraft'];

  protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'article_id', 'authorId', 'place_id', 'blockContentId', 'blockContentType', 'hublotContentId', 'hublotContentType'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'title' => 'required|min:3|max:200',
    'weather' => 'min:3|max:20',
    'city' => 'boolean',
    'blockContentType' => 'min:3|max:15',
    'hublotContentType' => 'min:3|max:15',
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
    return $this->belongsTo('Jetlag\Eloquent\Picture', 'blockContentId', 'id');
  }

  /**
   * Extracts the paragraph from the subrequest
   *
   * @param  array  $subRequest
   * @return  Jetlag\Eloquent\Paragraph the extracted paragraph
   */
  public function extract($subRequest)
  {
    if (array_key_exists('block_content', $subRequest))
    {
      $picture = new Picture;
      $picture->extract($subRequest['block_content']);
      $this->blockContent()->associate($picture);
    }

    if (array_key_exists('place', $subRequest))
    {
      $place = Place::create(array_merge(Place::$default_fillable_values, $subRequest['place']));
      $this->place()->associate($place);
    }
    $this->save();
    return $this;
  }

  /**
   * Gets the subrequest value matching the key
   * @param array subRequest a part of the request
   * @param string key the key matching the expected value
   * @param default the default value when no value matches the key
   *
   */
  public static function get($subRequest, $key, $default = null)
  {
    if (array_key_exists($key, $subRequest))
    {
      return $subRequest[$key];
    } else
    {
      return $default;
    }
  }
}
