<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'maps';

  protected $visible = ['id', 'caption', 'markers'];

  static $relationsToLoad = ['markers', 'markers.place'];

  public function paragraph()
  {
      return $this->morphOne('Jetlag\Eloquent\Paragraph', 'blockContent');
  }

  public function markers()
  {
    return $this->hasMany('Jetlag\Eloquent\Marker');
  }
}
