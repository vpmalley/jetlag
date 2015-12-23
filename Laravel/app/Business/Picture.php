<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Picture as StoredPicture;
use Jetlag\Eloquent\Link;
use Jetlag\Eloquent\Place;

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
   */
  public function fromUrl($authorId, $mediaUrl)
  {
    $this->authorId = $authorId;
    $this->setMediumDisplayUrl($mediaUrl);
  }

  /**
   *
   *
   * @param  int  $picId the id for the requested picture
   * @return  Jetlag\Business\Picture
   */
  public static function getById($picId)
  {
    $storedPicture = StoredPicture::where('id', $picId)->first();
    if ($storedPicture)
    {
      $picture = new Picture($storedPicture);
      $picture->id = $picId;
      // links
      $picture->smallPictureLink = Link::where('id', $storedPicture->smallPictureLinkId)->first();
      $picture->mediumPictureLink = Link::where('id', $storedPicture->mediumPictureLinkId)->first();
      $picture->bigPictureLink = Link::where('id', $storedPicture->bigPictureLinkId)->first();
      // location
      $picture->location = Place::where('id', $storedPicture->locationId)->first();

      return $picture;
    }
    // throw notfoundexception
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

  public function persist()
  {
    if (($this->id) && ($this->id > -1))
    {
      $picture = StoredPicture::getById($this->id);
    } else
    {
      $picture = new StoredPicture;
    }

    if ($this->smallPictureLink)
    {
      $this->smallPictureLink->save();
      $picture->smallPictureLinkId = $this->smallPictureLink->id;
    }
    if ($this->mediumPictureLink)
    {
      $this->mediumPictureLink->save();
      $picture->mediumPictureLinkId = $this->mediumPictureLink->id;
    }
    if ($this->bigPictureLink)
    {
      $this->bigPictureLink->save();
      $picture->bigPictureLinkId = $this->bigPictureLink->id;
    }

    $picture->authorId = $this->authorId;
    $picture->save();
    $this->id = $picture->id;
  }

  public function delete()
  {
    StoredPicture::getById($this->id)->delete();
  }
}
