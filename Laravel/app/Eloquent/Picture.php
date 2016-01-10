<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'pictures';

  /**
   * The attributes that are mass assignable.
   * A picture has so far 3 representations of different sizes (we can add more)
   * authorId refers (in authors table) to who created/can manage the picture
   * place_id refers to the place the picture was taken
   *
   * @var array
   */
  protected $fillable = ['smallPictureLink_id', 'mediumPictureLink_id', 'bigPictureLink_id', 'authorId', 'place_id'];

  protected $hidden = ['smallPictureLink_id', 'mediumPictureLink_id', 'bigPictureLink_id', 'authorId', 'place_id'];

  public function smallPictureLink()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'smallPictureLink_id');
  }

  public function mediumPictureLink()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'mediumPictureLink_id');
  }

  public function bigPictureLink()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'bigPictureLink_id');
  }

  public function place() {
    return $this->belongsTo('Jetlag\Eloquent\Place');
  }
}
