<?php namespace Jetlag\Http\Middleware;

use Closure;
use Response;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Jetlag\Eloquent\Author;

/**
 * Using this middleware in a controller is done as follows:
 * author:$table,$segmentIndex
 * $table is the name of the table for this type of element
 * $segmentIndex is the index of the segment that is the id of the element
 */
class AuthorOf {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $table, $segmentIndex)
	{
		if ($this->auth->user())
		{
      // the authenticated user
      $userId = $this->auth->user()->id;
      
      // the element that is queried
      $elementId = $request->segment($segmentIndex);
      
      // determine whether the user is author of the element
      $isAuthorOf = Author::isAuthorOf($userId, $table, $elementId);
      
			if ($isAuthorOf)
			{
				return $next($request);
			}
			else
			{
        return Response::make('You are not allowed to modify this element', 403); 
			}
		}

    return redirect()->guest('auth/login');
	}

}
