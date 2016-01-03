<?php namespace Jetlag\Http\Controllers\Web;

use Auth;
use Input;
use Response;
use Validator;
use Log;
use Illuminate\Http\Request;

use Jetlag\Http\Controllers\Controller;
use Jetlag\UserPublic;

class TravellerController extends Controller {

  /*
  |--------------------------------------------------------------------------
  | Traveller (user) Controller
  |--------------------------------------------------------------------------
  |
  |
  */


  /**
   * Create a new traveller controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Redirects to the default user page
   *
   * @param  int  $id
   */
  public function getIndex($id)
  {
    if (Auth::user()->id == $id)
    {
      return $this->getEdit($id);
    }
    return $this->getDisplay($id);
  }

  /**
   * Redirects to the default user page for the authenticated user
   *
   */
  public function getMe()
  {
    return $this->getEdit(Auth::user()->id);
  }

  /**
   * Display the public profile
   *
   * @param  int  $id
   */
  public function getDisplay($id)
  {
    $publicUser = UserPublic::where('id', $id)->first();
    return view('web.user.display', UserPublic::getForDisplay($publicUser, $id, 'This user prefers to keep some mystery about that ...'));
  }

  /**
   * Redirects to the public information display page for the authenticated user
   *
   */
  public function getMyDisplay()
  {
    return $this->getDisplay(Auth::user()->id);
  }

  /**
   * Edit user profile
   *
   * @param  int  $id
   */
  public function getEdit($id)
  {
	/* XXX: for now, the authenticated User can only edit his profile
	* and so the $id passed to the function is only to check it's the right route
	*/
	$authentUserID = Auth::user()->id;
    if ($authentUserID != $id)
    {
      return Response::make('You are trying to modify the wrong traveller', 403);
    }

    $publicUser = UserPublic::where('id', $authentUserID)->first();
    return view('web.user.edit', UserPublic::getForDisplay($publicUser, $authentUserID));
  }

  /**
   * Redirects to the public information edition page for the authenticated user
   *
   */
  public function getMyEdit()
  {
    return $this->getEdit(Auth::user()->id);
  }

  /**
   * Edit user profile
   *
   * @param  Illuminate\Http\Request  $request
   * @param  int  $id
   */
  public function postEdit(Request $request, $id)
  {
    if (Auth::user()->id != $id)
    {
      return Response::make('You are trying to modify the wrong traveller', 403);
    }

    $this->validate($request, UserPublic::$rules);

    $publicUser = UserPublic::getFromRequestAndPersist($request, $id);
    $display = UserPublic::getForDisplay($publicUser, $publicUser->userId);
    $display['saved'] = true;
    return view('web.user.edit', $display);
  }

}
