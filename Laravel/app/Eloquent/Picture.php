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

  protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'smallPictureLink_id', 'mediumPictureLink_id', 'bigPictureLink_id', 'authorId', 'place_id', 'article_id'];

  public function smallUrl()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'smallPictureLink_id');
  }

  public function mediumUrl()
  {
    return $this->belongsTo('Jetlag\Eloquent\Link', 'mediumPictureLink_id');
  }

  public function bigUrl()
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

    $this->extractAndBindLink($subRequest, 'small_url', $this->smallUrl());
    $this->extractAndBindLink($subRequest, 'url', $this->mediumUrl());
    $this->extractAndBindLink($subRequest, 'medium_url', $this->mediumUrl());
    $this->extractAndBindLink($subRequest, 'big_url', $this->bigUrl());
    $this->save();
    if (array_key_exists('place', $subRequest))
    {
      $place = Place::create(array_merge(Place::$default_fillable_values, $subRequest['place']));
      $this->place()->associate($place);
    }
    return $this;
  }

  private function extractAndBindLink($subRequest, $key, $relation)
  {
    if (array_key_exists($key, $subRequest))
    {
      $link = new Link;
      $link->extract($subRequest[$key]);
      $relation->associate($link);
    }
  }
}
