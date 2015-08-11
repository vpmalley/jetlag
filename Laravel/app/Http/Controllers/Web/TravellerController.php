<?php namespace Jetlag\Http\Controllers\Web;

use Auth;
use Input;
use Response;
use Validator;
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
    return $this->getSocial($id);
  }
  
  /**
   * Display the page for social activity
   * 
   * @param  int  $id
   */
  public function getSocial($id)
  {
		return view('welcome'); 
  }
  
  /**
   * Edit user profile
   * 
   * @param  int  $id
   */
  public function getEdit($id)
  {
    if (Auth::user()->id != $id)
    {
      return Response::make('You are trying to modify the wrong traveller', 403);
    }
    
    $user = Auth::user();
    $publicUser = UserPublic::where('userId', $user->id)->first();
    return view('web.user.edit', UserPublic::getForDisplay($publicUser, $user->id));
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
    
    $this->validate($request, [
        'name' => 'required|alpha_dash|min:3|max:200',
        'country' => 'required|alpha_dash|min:3|max:200',
    ]);
    
    $publicUser = UserPublic::getFromRequestAndPersist($request, $id);
    
    return view('web.user.edit', UserPublic::getForDisplay($publicUser, $publicUser->userId));
  }

}
