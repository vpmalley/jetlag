<?php namespace Jetlag\Http\Controllers\Web;

use Jetlag\Http\Controllers\Controller;
use Auth;
use Jetlag\UserPublic;
use Input;
use Illuminate\Http\Request;
use Response;

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
  
  // do not display the same information depending on who is logged
	//	$this->middleware('guest', ['except' => 'getLogout']);
  
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
      return Response::make('401 wrong user: you are not authorized to modify this user'); // return 401
    }
    
    $user = Auth::user();
    $publicUser = UserPublic::where('userId', $user->id)->first();
    $name = $user->name;
    $country = '';
    if ($publicUser)
    {
      $name = $publicUser->name;
      $country = $publicUser->country;
    }
		return view('web.user.edit', ['id' => $user->id, 'name' => $name, 'country' => $country]);
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
      return Response::make('401 wrong user: you are not authorized to modify this user'); // return 401
    }
    
    $publicUser = UserPublic::where('userId', $id)->first();
    if (!$publicUser)
    {
      $publicUser = new UserPublic;
      $publicUser->userId = $id;
    }
    
    if ($request->has('name'))
    {
      $publicUser->name = $request->input('name');
    }
    if ($request->has('country'))
    {
      $publicUser->country = $request->input('country');
    }
    
    if ($publicUser->id)
    {
      $publicUser->update();
    } else
    {
      $publicUser->save();
    }
    
		return view('web.user.edit', ['id' => $publicUser->userId, 'name' => $publicUser->name, 'country' => $publicUser->country]);
  }

}
