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

  /**
   * Extracts the picture from the subrequest
   *
   * @param  array  $subRequest
   * @return  Jetlag\Eloquent\Map the extracted map
   */
  public function extract($subRequest)
  {
    $this->id = array_key_exists('id', $subRequest) ? $subRequest['id'] : -1;
    $this->author_id = -1; // TODO authoring refacto

    $this->setField($subRequest, 'caption');
    $this->save();
    if (array_key_exists('markers', $subRequest))
    {
      foreach ($subRequest['markers'] as $markerSubRequest) {
        if (array_key_exists('id', $markerSubRequest))
        {
          $marker = Marker::find($markerSubRequest['id']);
        } else
        {
          $marker = new Marker;
        }
        $marker->extract($markerSubRequest);
        $this->markers()->save($marker);
      }
    }
    return $this;
  }

  public function setField($subRequest, $key) {
    if (array_key_exists($key, $subRequest)) {
      $this[$key] = $subRequest[$key];
    } else if (!isset($this[$key])) {
      $this[$key] = '';
    }
  }

}
