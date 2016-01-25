<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'markers';

  protected $visible = ['description', 'place'];

  public function map()
  {
    return $this->belongsTo('Jetlag\Eloquent\Map');
  }

  public function place()
  {
    return $this->belongsTo('Jetlag\Eloquent\Place');
  }
}
