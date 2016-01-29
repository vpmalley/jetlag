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

  protected $visible = ['id', 'title', 'small_url', 'medium_url', 'big_url', 'place'];

  static $relationsToLoad = ['small_url', 'medium_url', 'big_url', 'place'];

  public function paragraph()
  {
      return $this->morphOne('Jetlag\Eloquent\Paragraph', 'blockContent');
  }

  public function small_url()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'smallPictureLink_id');
  }

  public function medium_url()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'mediumPictureLink_id');
  }

  public function big_url()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'bigPictureLink_id');
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
