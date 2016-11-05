<?php

namespace Jetlag\Business;

use Auth;
use Jetlag\Eloquent\Author;

class ResourceAccess
{

  /**
  * Checks whether the logged in user can modify the resource as an owner. Rejects with error 403 otherwise.
  *
  * @param int authorId the id of the author for this resource
  */
  public static function wantsToOwnResource($authorId)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!Author::isOwner($id, $authorId))
    {
      abort(403);
    }
  }

  /**
  * Checks whether the logged in user can write the resource. Rejects with error 403 otherwise.
  *
  * @param int authorId the id of the author for this resource
  */
  public static function wantsToWriteResource($authorId)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!Author::isWriter($id, $authorId))
    {
      abort(403);
    }
  }

  /**
  * Checks whether the logged in user can read the resource. Rejects with error 403 otherwise.
  *
  * @param bool isPublic whether the resource is public
  * @param int authorId the id of the author for this resource
  */
  public static function wantsToReadResource($isPublic, $authorId)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!$isPublic && !Author::isReader($id, $authorId))
    {
      abort(403);
    }
  }

  /**
  * Checks whether the logged in user can read the resource. Returns false otherwise
  *
  * @param bool isPublic whether the resource is public
  * @param int authorId the id of the author for this resource
  */
  public static function canReadResource($isPublic, $authorId)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    return $isPublic || Author::isReader($id, $authorId);
  }
}
