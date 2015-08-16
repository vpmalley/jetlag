<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Article as StoredArticle;
use Jetlag\Business\Picture;

/**
 * 
 */
class Article
{
  /**
   * The title for the article
   * 
	 * @var string
   */
  protected $title;
  
  /**
   * The description for the article
   * 
	 * @var string
   */
  protected $descriptionText;
  
  /**
   * The description for the article
   * 
	 * @var Jetlag\Business\Picture
   */
  protected $descriptionPicture;
  
  /**
   * Whether the article is a draft
   * 
	 * @var boolean
   */
  protected $isDraft;
  
  /**
   * The paragraphs of this article
   * 
   * @var array
   */
  protected $paragraphs;

  /**
   * 
   */
  public function __construct($storedArticle, $picture, $paragraphs)
  {
    $this->title = $storedArticle->title;
    $this->descriptionText = $storedArticle->descriptionText;
    $this->isDraft = $storedArticle->isDraft;
    $this->descriptionPicture = $picture;
    $this->paragraphs = $paragraphs;
  }
  
  /** 
   * Retrieves and updates or constructs a UserPublic from the request and an id, then persists it
   * 
   * @param  int  $articleId the id for the requested article
   * @return  Jetlag\Business\Article
   */
  public static function getById($articleId)
  {
    $storedArticle = StoredArticle::where('id', $articleId)->firstOrFail();
    if ($storedArticle)
    {
      $picture = Picture::getById($storedArticle->descriptionMediaId);
      return new Article($storedArticle, $picture, []);
    }
  }
  
  /**
   * extracts the data for display
   * 
   * @return  array
   */
  public function getForDisplay()
  {
    $descriptionMediaUrl = NULL;
    if ($this->descriptionPicture) {
      $descriptionMediaUrl = $this->descriptionPicture->getSmallDisplayUrl();
    }
    return [
      'title' => $this->title,
      'descriptionText' => $this->descriptionText,
      'descriptionMediaUrl' => $descriptionMediaUrl,
      'isDraft' => $this->isDraft,
    ];
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
