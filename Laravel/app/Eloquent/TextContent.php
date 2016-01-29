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

  protected $fillable = ['content', 'authorId'];

  protected $visible = ['content', 'id'];

  public function paragraph()
  {
      return $this->morphOne('Jetlag\Eloquent\Paragraph', 'blockContent');
  }
}
