<?php

namespace Jetlag\Eloquent;

use Validator;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'pictures';

  protected $fillable = ['id'];

  protected $visible = ['id', 'title', 'small_url', 'medium_url', 'big_url', 'place'];

  static $relationsToLoad = ['small_url', 'medium_url', 'big_url', 'place'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'id' => 'numeric',
  ];

  public function paragraph()
  {
      return $this->morphOne('Jetlag\Eloquent\Paragraph', 'blockContent');
  }

  public function small_url()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'small_picture_link_id');
  }

  public function medium_url()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'medium_picture_link_id');
  }

  public function big_url()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'big_picture_link_id');
  }

  public function place() {
    return $this->belongsTo('Jetlag\Eloquent\Place');
  }

  /**
   * Extracts the picture from the subrequest
   *
   * @param  array  $subRequest
   * @return  Jetlag\Eloquent\Picture the extracted picture
   */
  public function extract($subRequest)
  {
    $this->id = array_key_exists('id', $subRequest) ? $subRequest['id'] : -1;
    $this->authorId = -1; // TODO authoring refacto

    $this->extractAndBindLink($subRequest, 'small_url', $this->small_url());
    $this->extractAndBindLink($subRequest, 'url', $this->medium_url());
    $this->extractAndBindLink($subRequest, 'medium_url', $this->medium_url());
    $this->extractAndBindLink($subRequest, 'big_url', $this->big_url());

    if (array_key_exists('place', $subRequest))
    {
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
    $this->save();
    return $this;
  }

  private function extractAndBindLink($subRequest, $key, $relation)
  {
    if (array_key_exists($key, $subRequest))
    {
      $validator = Validator::make($subRequest[$key], Link::$rules);
      if ($validator->fails()) {
        abort(400, $validator->errors());
      }
      if ($this[$key])
      {
        $link = $this[$key];
      } else
      {
        $link = new Link;
      }
      $link->extract($subRequest[$key]);
      $relation->associate($link);
    }
  }
}
