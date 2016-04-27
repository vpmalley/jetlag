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

  /**
   * The attributes that are mass assignable.
   *
   * *contentType can be 'textcontent' or 'gallery' so far
   *
   * @var array
   */
  protected $fillable = ['title', 'article_id', 'blockContentId', 'blockContentType', 'hublotContentId', 'hublotContentType', 'locationId', 'date', 'weather', 'authorId', 'isDraft'];

  /**
   * The rules for validating input
   */
  static $creationRules = [
        'title' => 'required|min:3|max:200',
        'weather' => 'min:3|max:20',
        'city' => 'boolean',
        'blockContentType' => 'required|min:3|max:15',
        'hublotContentType' => 'min:3|max:15',
    ];

  /**
   * The rules for validating input
   */
  static $updateRules = [
        'title' => 'required|min:3|max:200',
        'weather' => 'min:3|max:20',
        'city' => 'boolean',
        'blockContentType' => 'required|min:3|max:15',
        'hublotContentType' => 'min:3|max:15',
    ];

}
