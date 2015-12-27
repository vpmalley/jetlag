<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Article as StoredArticle;
use Jetlag\Eloquent\Author;
use Jetlag\Business\Picture;
use Jetlag\Business\Paragraph;
use Jetlag\UserPublic;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
   * The id for the author link to users who are authors of this article
   *
   * @var int
   */
  protected $authorId = -1;

  /**
   * The hash of user ids and their roles in authoring of this article
   *
   * @var array
   */
  protected $authorUsers = [];

  /**
   * The fillable properties
   */
  protected $fillable = ['title', 'descriptionText', 'descriptionMediaUrl', 'isDraft'];

  /**
   * The rules for validating input
   */
  static $rules = [
    'title' => 'required|min:3|max:200',
    'descriptionText' => 'max:500',
    'descriptionMediaUrl' => 'max:200',
    'isDraft' => 'boolean',
    ];

  public function __construct()
  {
  }

  /**
   *
   */
  public function fromDb($storedArticle, $picture, $paragraphs, $authorId, $authorUsers)
  {
    $this->id = $storedArticle->id;
    $this->title = $storedArticle->title;
    $this->descriptionText = $storedArticle->descriptionText;
    $this->isDraft = $storedArticle->isDraft;
    $this->descriptionPicture = $picture;
    $this->paragraphs = $paragraphs;
    $this->authorUsers = $authorUsers;
    $this->authorId = $storedArticle->authorId;
  }

  /**
   *
   */
  public function fromRequest($title, $descriptionText, $isDraft)
  {
    $this->title = $title;
    $this->descriptionText = $descriptionText;
    $this->isDraft = $isDraft;
    $this->authorUsers = [];
    $this->authorId = -1;
  }

  /**
   * Constructs an Article from an App\Eloquent\Article.
   *
   * @param  StoredArticle  $storedArticle the stored article
   */
  public function fromStoredArticle($storedArticle)
  {
    $picture = NULL;
    if ($storedArticle->descriptionPicture)
    {
      $picture = new Picture;
      $picture->fromStoredPicture($storedArticle->descriptionPicture);
    }
    $paragraphs = Paragraph::getAllForArticle($storedArticle->id);
    $authorUsers = Author::getUserRoles($storedArticle->authorId);
    $this->fromDb($storedArticle, $picture, $paragraphs, $storeArticle->authorId, $authorUsers);
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
      $descriptionMediaUrl = $this->descriptionPicture->getMediumDisplayUrl();
    }
    return $descriptionMediaUrl;
  }

  public function hasDescriptionPicture()
  {
    return isset($this->descriptionPicture);
  }

  public function getDescriptionPicture()
  {
    return $this->descriptionPicture;
  }

  public function setDescriptionPicture($picture)
  {
    $this->descriptionPicture = $picture;
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
   * @param array hash of user ids and roles to make them as the new authors of this article
   */
  public function updateAuthorUsers($authorUsers)
  {
    if (!empty($authorUsers))
    {
      if ($this->authorId < 0)
      {
        $this->authorId = Author::getNewAuthorId();
      }
      $this->authorId = Author::updateAuthorUsers($this->authorId, $authorUsers);
      $this->authorUsers = $authorUsers;
    }
  }

  /**
   * Retrieves and updates or constructs a UserPublic from the request and an id, then persists it
   *
   * @param  int  $articleId the id for the requested article
   * @return  Jetlag\Business\Article
   */
  public static function getById($articleId)
  {
    $storedArticle = StoredArticle::findOrFail($articleId);
    if ($storedArticle)
    {
      $article = new Article;
      $article->fromStoredArticle($storedArticle);
      return $article;
    }
    throw new ModelNotFoundException;
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
    $authorNames = UserPublic::select('name')->whereIn('id', array_keys($this->authorUsers))->get();
    foreach($authorNames as $authorName)
    {
      $authorNameLabel .= $authorName['name'];
    }

    return [
      'title' => $this->title,
      'descriptionText' => $this->descriptionText,
    //  'descriptionMediaUrl' => $descriptionMediaUrl,
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
    return [
      'id' => $this->id,
  	  'title' => $this->title,
      'url' => url('/article/' . $this->id),
  	  'descriptionText' => $this->descriptionText,
  	  'descriptionMedia' => $this->descriptionPicture->getForRest(),
      'isDraft' => $this->isDraft,
  	  'paragraphs' => $this->paragraphs,
  	  'authorUsers' => $this->authorUsers,
    ];
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
  	  'title' => $this->title,
      'url' => url('/article/' . $this->id),
  	  'descriptionText' => $this->descriptionText,
  	  'descriptionMedia' => $this->descriptionPicture->getForRest(),
  	  'paragraphs' => $this->paragraphs,
  	  'authorUsers' => $this->authorUsers,
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
    $articles = [];
    $authorIds = Author::getAuthorsForUser($userId);
    $storedArticles = StoredArticle::whereIn('authorId', $authorIds)->get();
    foreach ($storedArticles as $article)
    {
      $articles[] = Article::fromStoredArticle($article);
    }
    return $articles;
  }

  public function persist()
  {
    if (($this->id) && ($this->id > -1))
    {
      $article = StoredArticle::findOrFail($this->id);
    } else
    {
      $article = new StoredArticle;
    }

    $article->title = $this->title;
    $article->descriptionText = $this->descriptionText;
    $article->isDraft = $this->isDraft;
    $article->authorId = $this->authorId;
    $article->save();

    if ($this->descriptionPicture)
    {
      $this->descriptionPicture->persist();
      $article->descriptionPicture()->save($this->descriptionPicture->getStoredPicture());
    }

    $this->id = $article->id;
    return $this->id;
  }

  public function delete()
  {
    StoredArticle::findOrFail($this->id)->delete();
  }
}
