<?php

namespace Jetlag\Eloquent;

use Validator;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'markers';

  protected $fillable = ['id', 'description'];

  protected $visible = ['id', 'description', 'place'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'id' => 'numeric',
    'description' => 'string|min:3|max:500',
  ];

  public function map()
  {
    return $this->belongsTo('Jetlag\Eloquent\Map');
  }

  public function place()
  {
    return $this->belongsTo('Jetlag\Eloquent\Place');
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

    $this->setField($subRequest, 'description');
    $this->extractAndBindPlace($subRequest);
    $this->save();
    return $this;
  }

  public function extractAndBindPlace($subRequest) {
    if (array_key_exists('place', $subRequest))
    {
      $validator = Validator::make($subRequest['place'], Place::$rules);
      if ($validator->fails()) {
        abort(400, $validator->errors());
      }
      if ($this->place)
      {
        $this->place->fill($subRequest['place']);
        $this->place->save();
      } else
      {
        $placeSubRequest = array_merge(Place::$default_fillable_values, $subRequest['place']);
        $place = Place::create($placeSubRequest);
        $this->place()->associate($place);
      }
    }
  }

  public function setField($subRequest, $key) {
    if (array_key_exists($key, $subRequest)) {
      $this[$key] = $subRequest[$key];
    } else if (!isset($this[$key])) {
      $this[$key] = '';
    }
  }
}
