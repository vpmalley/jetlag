<?php

namespace Jetlag\Business;

use Jetlag\Eloquent\Paragraph as StoredParagraph;
use Jetlag\Eloquent\Author;
use Jetlag\Eloquent\Link;
use Jetlag\Eloquent\Place;

/**
 *
 */
class Paragraph
{
  /**
   * The title of the paragraph
   *
   * @var string
   */
  protected $title;

  /**
   * The content in the block
   *
   * @var Jetlag\Eloquent\Link
   */
  protected $blockContent;

  /**
   * The content in the hublot
   *
   * @var Jetlag\Eloquent\Link
   */
  protected $hublotContent;

  /**
   * The place where the picture was taken
   *
   * @var Jetlag\Eloquent\Place
   */
  protected $location;

  /**
   * The date of the travel in this paragraph
   *
   * @var date
   */
  protected $date;

  /**
   * The description for the weather
   *
   * @var string
   */
  protected $weather;

  /**
   * Whether the paragraph is a draft or is published
   *
   * @var boolean
   */
  protected $isDraft;

  /**
   * The array of user ids who are authors of this paragraph
   *
   * @var array
   */
  protected $authorUsers;

  /**
   *
   */
  public function __construct($storedParagraph)
  {
    $this->title = $storedParagraph->title;
    $this->date = $storedParagraph->date;
    $this->weather = $storedParagraph->weather;
    $this->isDraft = $storedParagraph->isDraft;
  }

  public static function getFromStoredParagraph($storedParagraph)
  {
    $paragraph = new Paragraph($storedParagraph);
    // contents
    // TODO
    // location
    $paragraph->location = $this->place;
    // authors
    $paragraph->authorUsers = Author::getUsers($storedParagraph->authorId);

    return $paragraph;
  }

  /**
   *
   *
   * @param  int  $paraId the id for the requested paragraph
   * @return  Jetlag\Business\Paragraph
   */
  public static function getById($paraId)
  {
    $storedParagraph = StoredParagraph::where('id', $paraId)->firstOrFail();

    if ($storedParagraph)
    {
      return Paragraph::getFromStoredParagraph($storedParagraph);
    }
  }

  public static function getAllForArticle($articleId)
  {
    $paragraphs = [];
    $storedParagraphs = StoredParagraph::where('article_id', $articleId)->get();
    foreach($storedParagraphs as $storedParagraph)
    {
      $paragraphs[] = Paragraph::getFromStoredParagraph($storedParagraph);
    }
    return $paragraphs;
  }

  public function getTitle()
  {
    return $this->title;
  }
}
