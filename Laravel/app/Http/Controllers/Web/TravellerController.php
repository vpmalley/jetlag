<?php namespace Jetlag\Http\Controllers\Web;

use Jetlag\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

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
   */
  public function getIndex($id)
  {
    return $this->getSocial($id);
  }
  
  /**
   * Display the page for social activity
   */
  public function getSocial($id)
  {
		return view('welcome'); 
  }
  
  /**
   * Edit user profile
   */
  public function getEdit()
  {
		return view('welcome');
  }

}
