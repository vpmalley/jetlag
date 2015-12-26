<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Log;

class Article extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'articles';

  /**
   * The attributes that are mass assignable.
   * title is the title of the article
   * descriptionText is a text description of the article
   * isDraft is a boolean, to indicate whether the article is a draft
   *
   * @var array
   */
  public $fillable = ['title', 'descriptionText', 'isDraft', 'authorId'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'title' => 'min:3|max:200',
    'descriptionText' => 'min:3|max:500'
    ];

  public function descriptionPicture()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture');
  }
}
