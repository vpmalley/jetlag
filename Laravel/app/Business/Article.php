<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Article as StoredArticle;
use Jetlag\Eloquent\Author;
use Jetlag\Business\Picture;
use Jetlag\Business\Paragraph;
use Jetlag\UserPublic;
use Illuminate\Http\Request;
use Log;

/**
 * 
 */
class Article
{

  /**
   * The id for the article, matching a Jetlag\Eloquent\Article stored in DB
   * 
	 * @var int
   */
  protected $id;
  
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
   * The fillable properties
   */
  protected $fillable = ['title', 'descriptionText', 'descriptionMediaUrl', 'isDraft'];
  
  /**
   * The rules for validating input
   */
  static $rules = [
    'title' => 'min:3|max:200',
    'descriptionText' => 'min:3|max:500',
    'descriptionMediaUrl' => 'max:200',
    'isDraft' => 'boolean',
    ];

  public function __construct()
  {
  }
  
  /**
   * 
   */
  public function fromDb($storedArticle, $picture, $paragraphs, $authorUsers)
  {
    $this->id = $storedArticle->id;
    $this->title = $storedArticle->title;
    $this->descriptionText = $storedArticle->descriptionText;
    $this->isDraft = $storedArticle->isDraft;
    $this->descriptionPicture = $picture;
    $this->paragraphs = $paragraphs;
    $this->authorUsers = $authorUsers;
  }
  
  /**
   * 
   */
  public function fromRequest($title, $descriptionText, $descriptionMediaUrl, $isDraft, $authorId)
  {
    $this->title = $title;
    $this->descriptionText = $descriptionText;
    $this->isDraft = $isDraft;
    $this->descriptionPicture = new Picture;
    $this->descriptionPicture->fromUrl($authorId, $descriptionMediaUrl);
    $this->authorId = $authorId;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getDescriptionText()
  {
    return $this->descriptionText;
  }

  public function setDescriptionText($descriptionText)
  {
    $this->descriptionText = $descriptionText;
  }

  public function getDescriptionMediaUrl()
  {
    $descriptionMediaUrl = '';
    if ($this->descriptionPicture)
    {
      $descriptionMediaUrl = $this->descriptionPicture->mediumPictureLink->url;
    }
    return $descriptionMediaUrl;
  }

  public function setDescriptionMediaUrl($descriptionMediaUrl)
  {
    if ($this->descriptionPicture)
    {
      $this->descriptionPicture->delete();
    }
    $this->descriptionPicture = new Picture;
    $this->descriptionPicture->fromUrl(-1, $descriptionMediaUrl);
  }

  public function isDraft()
  {
    return $this->isDraft;
  }

  public function setIsDraft($isDraft)
  {
    $this->isDraft = $isDraft;
  }

  /**
   * Retrieves and updates or constructs a UserPublic from the request and an id, then persists it
   * 
   * @param  int  $articleId the id for the requested article
   * @return  Jetlag\Business\Article
   */
  public static function getById($articleId)
  {
    $storedArticle = StoredArticle::getById($articleId);
    if ($storedArticle)
    {
      $picture = Picture::getById($storedArticle->descriptionMediaId);
      $paragraphs = Paragraph::getAllForArticle($articleId);
      $authorUsers = Author::getUsers($storedArticle->authorId);
      $article = new Article;
      $article->fromDb($storedArticle, $picture, $paragraphs, $authorUsers);
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
   * extracts the data for showing as REST
   * 
   * @return  array
   */
  public function getForRest()
  {
    $content = $this->getForDisplay();
    $content['url'] = url('/article/' . $this->id);
    return $content;
  }
  
  /**
   * extracts id and url
   * 
   * @return  array
   */
  public function getForRestIndex()
  {
    return [
      'id' => $this->id,
      'url' => url('/article/' . $this->id),
    ];
  }
  
  /**
   * Retrieves and updates or constructs a UserPublic from the request and an id, then persists it
   * 
   * @param  int  $userId the id for the requested user
   * @return  array
   */
  public static function getAllForUser($userId)
  {
    // TODO get all articles whose author has id $userId
    return [Article::getById(1)];
  }

  public function persist()
  {
    $this->descriptionPicture->persist();
    
    if (($this->id) && ($this->id > -1))
    {
      $article = StoredArticle::getById($this->id);
    } else
    {
      $article = new StoredArticle;
    }

    $article->title = $this->title;
    $article->descriptionText = $this->descriptionText;
    $article->isDraft = $this->isDraft;
    //$article->authorId = $this->authorId;

    $article->save();

    $this->id = $article->id;
  }

  public function delete()
  {
    StoredArticle::getById($this->id)->delete();
  }
}
