<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Log;

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


  protected $casts = [
      'isDraft' => 'boolean',
      'isPublic' => 'boolean',
  ];

  /**
  * The rules for validating input
  */
  static $rules = [
    'id' => 'numeric',
    'title' => 'string|required|min:3|max:200',
    'description_text' => 'string|min:3|max:500',
    'is_draft' => 'boolean',
    'is_public' => 'boolean',
  ];

  protected $dates = ['deleted_at'];

  public function descriptionPicture()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture');
  }

  public function paragraphs()
  {
    return $this->hasMany('Jetlag\Eloquent\Paragraph');
  }
}
