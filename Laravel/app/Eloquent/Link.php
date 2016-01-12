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
   * authorId is the id for author(s) in the authors table
	 *
	 * @var array
	 */
	protected $fillable = ['caption', 'storage', 'url', 'authorId'];

	protected $hidden = ['id', 'created_at', 'updated_at', 'deleted_at', 'storage', 'authorId'];

  /**
   * The rules for validating input
   */
  static $rules = [
        'caption' => 'min:3|max:200',
        'url' => 'min:3|max:200',
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
    return $this->hasOne('Jetlag\Eloquent\Picture', 'smallPictureLink_id');
  }

  public function pictureAsMediumLink()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture', 'mediumPictureLink_id');
  }

  public function pictureAsBigLink()
  {
    return $this->hasOne('Jetlag\Eloquent\Picture', 'bigPictureLink_id');
  }
}
