<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Picture as StoredPicture;
use Jetlag\Eloquent\Link;
use Jetlag\Eloquent\Place;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 *
 */
class Picture
{

  /**
   * The id for the article, matching a Jetlag\Eloquent\Article stored in DB
   *
   * @var int
   */
  protected $id;

  /**
   * The link to the small picture
   *
   * @var Jetlag\Eloquent\Link
   */
  protected $smallPictureLink;

  /**
   * The link to the medium picture
   *
   * @var Jetlag\Eloquent\Link
   */
  protected $mediumPictureLink;

  /**
   * The link to the big picture
   *
   * @var Jetlag\Eloquent\Link
   */
  protected $bigPictureLink;

  /**
   * The id for the author
   *
   * @var int
   */
  protected $authorId;

  /**
   * The place where the picture was taken
   *
   * @var Jetlag\Eloquent\Place
   */
  protected $location;

  // probably whether the photo is public, its license, ...

  public function __construct()
  {
  }

  /**
   *
   */
  public function fromDb($storedPicture)
  {
    $this->id = $storedPicture->id;
    $this->authorId = $storedPicture->authorId;
  }

  /**
   *
   *
   * @param  int  $picId the id for the requested picture
   * @return  Jetlag\Business\Picture
   */
  public static function getById($picId)
  {
    $storedPicture = StoredPicture::findOrFail($picId);
    if ($storedPicture)
    {
      $picture = new Picture;
      $picture->fromStoredPicture($storedPicture);
      return $picture;
    }
    throw new ModelNotFoundException;
  }

  public function fromStoredPicture($storedPicture)
  {
    $this->id = $storedPicture->id;
    // links
    $this->smallPictureLink = $storedPicture->smallPictureLink;
    $this->mediumPictureLink = $storedPicture->mediumPictureLink;
    $this->bigPictureLink = $storedPicture->bigPictureLink;
    // location
    $this->location = Place::find($storedPicture->locationId);
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getSmallDisplayUrl()
  {
    $url = NULL;
    if ($this->smallPictureLink)
    {
      $url = $this->smallPictureLink->getDisplayUrl();
    }
    return $url;
  }

  public function setSmallDisplayUrl($url)
  {
    if ($url != $this->getSmallDisplayUrl())
    {
      $this->smallPictureLink = new Link;
      $this->smallPictureLink->fromUrl($url);
    }
  }

  public function getMediumDisplayUrl()
  {
    $url = NULL;
    if ($this->mediumPictureLink)
    {
      $url = $this->mediumPictureLink->getDisplayUrl();
    }
    return $url;
  }

  public function setMediumDisplayUrl($url)
  {
    if ($url != $this->getMediumDisplayUrl())
    {
      $this->mediumPictureLink = new Link;
      $this->mediumPictureLink->fromUrl($url);
    }
  }

  public function getBigDisplayUrl()
  {
    $url = NULL;
    if ($this->bigPictureLink)
    {
      $url = $this->bigPictureLink->getDisplayUrl();
    }
    return $url;
  }

  public function setBigDisplayUrl($url)
  {
    if ($url != $this->getBigDisplayUrl())
    {
      $this->bigPictureLink = new Link;
      $this->bigPictureLink->fromUrl($url);
    }
  }

  public function getAuthorId()
  {
    return $this->$authorId;
  }

  public function setAuthorId($authorId)
  {
    $this->authorId = $authorId;
  }

  public function getForRest()
  {
    $content = [];
    $content['id'] = $this->getId();
    $content['smallUrl'] = $this->getSmallDisplayUrl();
    $content['mediumUrl'] = $this->getMediumDisplayUrl();
    $content['bigUrl'] = $this->getBigDisplayUrl();
    return $content;
  }

  /**
   * Retrieves and updates or constructs a UserPublic from the request and an id, then persists it
   *
   * @param  int  $userId the id for the requested user
   * @return  array
   */
  public function getAllForUser($userId)
  {
  }

  public function getStoredPicture()
  {
    $picture = StoredPicture::findOrNew($this->id);
    $picture->authorId = $this->authorId;
    return $picture;
  }

  public function persist()
  {
    $picture = $this->getStoredPicture();
    $picture->save();
    if ($this->smallPictureLink)
    {
      $picture->smallPictureLink()->save($this->smallPictureLink);
    }
    if ($this->mediumPictureLink)
    {
      $picture->mediumPictureLink()->save($this->mediumPictureLink);
    }
    if ($this->bigPictureLink)
    {
      $picture->bigPictureLink()->save($this->bigPictureLink);
    }
    $this->id = $picture->id;
  }

  public function delete()
  {
    StoredPicture::findOrFail($this->id)->delete();
  }
}
