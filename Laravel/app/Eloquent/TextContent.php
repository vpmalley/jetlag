<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TextContent extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'textcontents';

  protected $fillable = ['content', 'id'];

  protected $visible = ['content', 'id'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'id' => 'numeric',
    'content' => 'string|required|min:3|max:10000',
  ];

  public function paragraph()
  {
      return $this->morphOne('Jetlag\Eloquent\Paragraph', 'blockContent');
  }
}
