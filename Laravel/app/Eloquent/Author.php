<?php

namespace Jetlag\Eloquent;

use Log;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jetlag\UserPublic;

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
  protected $fillable = ['role'];

  /**
  * The rules for validating input
  */
  static $rules = [
    'role' => 'alpha|required|min:5|max:6',
  ];

  const ROLE_OWNER = 'owner';
  const ROLE_WRITER = 'writer';
  const ROLE_READER = 'reader';

  /**
  * @param  int  $userId the user we want to check is an owner (usually the logged in user)
  * @param  int  $authorId the authorId of the element
  * @return boolean whether the user is an owner of the element
  */
  public static function isOwner($userId, $authorId)
  {
    // the number of rows matching the authenticated user and element's author : either 0 or 1
    $count = Author::where('user_id', $userId)->where('author_id', $authorId)->where('role', self::ROLE_OWNER)->count();
    return ($count > 0);
  }

  /**
  * @param  int  $userId the user we want to check is a writer (usually the logged in user)
  * @param  int  $authorId the authorId of the element
  * @return boolean whether the user is a writer of the element
  */
  public static function isWriter($userId, $authorId)
  {
    // the number of rows matching the authenticated user and element's author : either 0 or 1
    $count = Author::where('user_id', $userId)->where('author_id', $authorId)->where(function ($query) {
      $query->where('role', self::ROLE_OWNER)
      ->orWhere('role', self::ROLE_WRITER);
    })->count();
    return ($count > 0);
  }

  /**
  * Warning, this does not check whether the element is public. A user may not be a reader but the element may be publicly visible
  * @param  int  $userId the user we want to check is a reader (usually the logged in user)
  * @param  int  $authorId the authorId of the element
  * @return boolean whether the user is a reader of the element
  */
  public static function isReader($userId, $authorId)
  {
    // the number of rows matching the authenticated user and element's author : either 0 or 1
    $count = Author::where('user_id', $userId)->where('author_id', $authorId)->count();
    Log::debug("user with id " . $userId . " appears with author_id=" . $authorId . " " . $count . " times.");
    return ($count > 0);
  }

  /**
  * Returns the array of user ids matching that author id
  */
  public static function getUsers($authorId)
  {
    $userIds = [];
    $authors = Author::where('author_id', $authorId)->get();
    foreach ($authors as $author)
    {
      $userIds[] = $author->user_id;
    }
    $users = UserPublic::whereIn('id', $userIds)->get();
    return $users;

  }

  /**
  * Returns the hash of user ids and their role
  */
  public static function getUserRoles($authorId)
  {
    $userRoles = [];
    $authors = Author::where('author_id', $authorId)->get();
    foreach ($authors as $author)
    {
      $userRoles[$author->user_id] = $author->role;
    }
    return $userRoles;

  }

  /**
  *
  * @return var array an array of author ids
  */
  public static function getAuthorsForUser($userId)
  {
    $authorIds = [];
    $authors = Author::where('user_id', $userId)->get();
    foreach ($authors as $author)
    {
      $authorIds[] = $author->author_id;
    }
    return $authorIds;
  }

  /**
  * Determines the role of a user as an author
  *
  * @param authorId the id of the author
  * @param userId the id of the user
  * @return var string a role, or NULL
  */
  public static function getRole($authorId, $userId)
  {
    $author = Author::where('user_id', $userId)->where('author_id', $authorId)->first();
    if ($author)
    {
      return $author->role;
    }
    return NULL;
  }

  /**
  *
  * @return an author id that has never been given
  */
  public static function getNewAuthorId()
  {
    return DB::table('authors')->max('author_id') + 1;
  }

  /**
  * Compares the current authors and the new ones, and persists a new Author object if different
  * @param authorId the id for the current author
  * @param newAuthorUsers hash of authors containing a userId and the user's role
  * @return int the authorId for this author
  */
  public static function updateAuthorUsers($authorId, $newAuthorUsers)
  {
    if ($newAuthorUsers != self::getUserRoles($authorId))
    {
      $authors = Author::where('author_id', $authorId)->get();
      foreach ($authors as $author) // deleting existing pairs authorId/userId
      {
        $author->delete();
      }
      foreach ($newAuthorUsers as $newUserId => $newRole) // inserting new pairs authorId/userId
      {
        Log::debug("author " . $authorId . " with user " . $newUserId . " is now " . $newRole);
        $newAuthor = new Author;
        $newAuthor->author_id = $authorId;
        $newAuthor->user_id = $newUserId;
        $newAuthor->role = $newRole;
        $newAuthor->save();
      }
    }
    return $authorId;
  }

}
