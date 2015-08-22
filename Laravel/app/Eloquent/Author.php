<?php

namespace Jetlag\Eloquent;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * An author is one or multiple users creating or managing elements
 */
class Author extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'authors';

	/**
	 * The attributes that are mass assignable.
   * authorId is the id for author(s)
   * userId is the id for a user being one of the author(s)
   * name is the optional name of the user in this author group
	 *
	 * @var array
	 */
	protected $fillable = ['authorId', 'userId', 'name'];
  
  /**
   * The rules for validating input
   */
  static $rules = [
        'name' => 'min:3|max:100',
    ];

  /**
   * @param  int  $userId the user we want to check is an author (usually the logged in user)
   * @param  string  $elementTable the table for the element we want to check the author of
   * @param  int  $elementId the id of the element in that table
   * @return boolean whether the user is an author of the element
   */
  public static function isAuthorOf($userId, $elementTable, $elementId)
  {
    $elements = DB::table($elementTable)->where('id', $elementId)->get();
    
    // if the element does not exist, show it is not found
    if (0 == count($elements))
    {
      throw new ModelNotFoundException;
    }
    
    // the author id for this element
    $authorId = $elements[0]->authorId;
    
    // the number of rows matching the authenticated user and element's author : either 0 or 1
    $count = Author::where('userId', $userId)->where('authorId', $authorId)->count();
    
    $isAuthorOf = false;
    if (1 == $count)
    {
      $isAuthorOf = true;
    }
    return $isAuthorOf;
  }
  
  /**
   * Returns the array of user ids matching that author id
   */
  public static function getUsers($authorId)
  {
    $userIds = [];
    $authors = Author::where('authorId', $authorId)->get();
    foreach ($authors as $author)
    {
      $userIds[] = $author->userId;
    }
    return $userIds;
  }  
}
