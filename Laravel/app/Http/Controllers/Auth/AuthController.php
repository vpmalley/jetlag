<?php namespace Jetlag\Http\Controllers\Auth;

use Validator;
use Jetlag\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Jetlag\User;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}
  
  /**
   * Contains the validation rules for new users of the application.
   */
  public function validator(array $user)
  {
    $validator = Validator::make($user, []);
    return $validator;
  }

  
  /**
   * Responsible for creating new App\User records in your database using the Eloquent ORM
   */
  public function create(array $user)
  {
     //'name' => 'Yoyo', 'email' => 'yoyo@yopmail.com', 'password' => 'yoyo', 'password_confirmation' => 'yoyo'))) in RegistersUsers.php line 38
    $newUser = new User;
    $newUser->name = $user['name'];
    $newUser->email = $user['email'];
    $newUser->password = $user['password'];
    $newUser->save();
    return $newUser;
  }

}
