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
   * locationId refers to the place the picture was taken
   *
   * @var array
   */
  protected $fillable = ['smallPictureLinkId', 'mediumPictureLinkId', 'bigPictureLinkId', 'authorId', 'locationId'];
}
