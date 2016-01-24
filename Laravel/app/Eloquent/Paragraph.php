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

  protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'article_id', 'authorId', 'place_id', 'block_content_id', 'hublotContentId', 'hublotContentType'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'title' => 'required|min:3|max:200',
    'weather' => 'min:3|max:20',
    'city' => 'boolean',
    'block_content_type' => 'min:3|max:30',
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
        if (array_key_exists('id', $subRequest))
        {
          $picture = Picture::find($subRequest['id']);
        } else
        {
          $picture = new Picture;
        }
        $picture->extract($subRequest['block_content']);
        $this->blockContent()->associate($picture);
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
