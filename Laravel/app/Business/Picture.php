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
  
  /**
   * 
   */
  public function __construct($storedPicture)
  {
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
    $storedPicture = StoredPicture::where('id', $picId)->first();
    if ($storedPicture)
    {
      $picture = new Picture($storedPicture);
      
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
  
  public function getSmallDisplayUrl()
  {
    $url = NULL;
    if ($this->smallPictureLink)
    {
      $url = $this->smallPictureLink->getDisplayUrl();
    }
    return $url; 
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
  
  public function getBigDisplayUrl()
  {
    $url = NULL;
    if ($this->bigPictureLink)
    {
      $url = $this->bigPictureLink->getDisplayUrl();
    }
    return $url; 
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

}
