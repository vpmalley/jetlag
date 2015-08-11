<?php

namespace Jetlag;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Public data about a user. Typically includes a public name, a profile picture, ...
 * The idea is to separate the information that is public from what is private or used for authentication.
 * Like that, looking at someone's profile does not require to access the private information.
 */
class UserPublic extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'publicusers';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['userId', 'name', 'profilePicUrl', 'country', 'city'];
  
  /**
   * Retrieves and updates or constructs a UserPublic from the request and an id, then persists it
   * 
   * @param  int  $userId the id for the requested user
   * @param  Illuminate\Http\Request  $request the request containing data to create/update the user public information
   * @return  UserPublic
   */
  public static function getFromRequestAndPersist(Request $request, $userId)
  {
    $publicUser = UserPublic::where('userId', $userId)->first();
    if (!$publicUser)
    {
      $publicUser = new UserPublic;
      $publicUser->userId = $userId;
    }

    foreach ($publicUser->fillable as $property)
    {
      if ($request->has($property))
      {
        $publicUser[$property] = $request->input($property);
      }
    }
    
    if ($publicUser->id)
    {
      $publicUser->update();
    } else
    {
      $publicUser->save();
    }
    
    return $publicUser;
  }
  
  /**
   * Extract information for display
   * 
   * @param  UserPublic  $publicUser the user, can be null
   * @param  int  $userId the id for the requested user
   * @return  array
   */
  public static function getForDisplay($publicUser, $userId, $defaultContent = '')
  {
    $display = [];
    if ($publicUser)
    {
      foreach ($publicUser->fillable as $property)
      {
        $display[$property] = $publicUser[$property];
        if ('' == $display[$property])
        {
          $display[$property] = $defaultContent;
        }
      }
    } else 
    {
      $publicUser = new UserPublic;
      foreach ($publicUser->fillable as $property)
      {
        $display[$property] = $defaultContent;
      }
      $display['userId'] = $userId;
    }
    $display['saved'] = false;
    return $display;
  }

}
