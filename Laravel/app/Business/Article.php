<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Article as StoredArticle;
use Jetlag\Eloquent\Author;
use Jetlag\Business\Picture;
use Jetlag\Business\Paragraph;
use Jetlag\UserPublic;

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
   * The array of user ids who are authors of this article
   *
   * @var array
   */
  protected $authorUsers;

  /**
   * 
   */
  public function __construct($storedArticle, $picture, $paragraphs, $authorUsers)
  {
    $this->title = $storedArticle->title;
    $this->descriptionText = $storedArticle->descriptionText;
    $this->isDraft = $storedArticle->isDraft;
    $this->descriptionPicture = $picture;
    $this->paragraphs = $paragraphs;
    $this->authorUsers = $authorUsers;
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
      $paragraphs = Paragraph::getAllForArticle($articleId);
      $authorUsers = Author::getUsers($storedArticle->authorId);
      $article = new Article($storedArticle, $picture, $paragraphs, $authorUsers);
      return $article;
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
    
    $authorNameLabel = '';
    $authorNames = UserPublic::select('name')->whereIn('id', $this->authorUsers)->get();
    foreach($authorNames as $authorName)
    {
      $authorNameLabel .= $authorName['name'];
    }
    
    return [
      'title' => $this->title,
      'descriptionText' => $this->descriptionText,
      'descriptionMediaUrl' => $descriptionMediaUrl,
      'isDraft' => $this->isDraft,
      'authorName' => $authorNameLabel,
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
