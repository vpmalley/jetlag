<?php

namespace Jetlag\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'links';

  /**
  * The attributes that are mass assignable.
  * caption is a description of the link
  * storage is either 'web' or a disk in terms of Laravel file system (typically 'local' or 's3')
  * url is either the url to the resource accessible online, or a path to a file if storage is local or s3
  * author_id is the id for author(s) in the authors table
  *
  * @var array
  */
  protected $fillable = ['caption', 'url'];

  protected $visible = ['url', 'caption'];

  /**
  * The rules for validating input
  */
  static $rules = [
    'caption' => 'string|max:200',
    'url' => 'string|max:200',
  ];

  public function fromUrl($url)
  {
    $this->storage = 'web';
    $this->url = $url;
  }

  public function getDisplayUrl()
  {
    if ('web' == $this->storage)
    {
      return $this->url;
    }
  }

  public function pictureAsSmallLink()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture', 'small_picture_link_id');
  }

  public function pictureAsMediumLink()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture', 'medium_picture_link_id');
  }

  public function pictureAsBigLink()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture', 'big_picture_link_id');
  }

  /**
  * Extracts the link from the subrequest
  *
  * @param  array  $subRequest
  * @return  Jetlag\Eloquent\Link the extracted link
  */
  public function extract($subRequest)
  {
    if (!array_key_exists('url', $subRequest) && !isset($this->url))
    {
      abort(400);
    }
    if (array_key_exists('url', $subRequest))
    {
      $this->fromUrl($subRequest['url']);
    }
    if (array_key_exists('caption', $subRequest))
    {
      $this->caption = $subRequest['caption'];
    } else if (!isset($this->caption)) {
      $this->caption = '';
    }
    $this->save();
    return $this;
  }
}
