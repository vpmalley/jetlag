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

  public function place() {
    return $this->belongsTo('Jetlag\Eloquent\Place');
  }

  public function blockContent() {
    return $this->belongsTo('Jetlag\Eloquent\Picture', 'blockContentId', 'id');
  }


}
