<?php namespace Jetlag\Http\Middleware;

use Closure;
use Response;
use DB;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Jetlag\Eloquent\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
      $elements = DB::table($table)->where('id', $elementId)->get();
      
      // if the element does not exist, show it is not found
      if (0 == count($elements))
      {
        throw new ModelNotFoundException;
      }
      
      // the author id for this element
      $authorId = $elements[0]->authorId;
      
      // the row matching the authenticated user and element's author : either 0 or 1
      $count = Author::where('userId', $userId)->where('authorId', $authorId)->count();
      
			if (1 == $count)
			{
				return $next($request);
			}
			else // 0
			{
        return Response::make('You are not allowed to modify this element', 403); 
			}
		}

    return redirect()->guest('auth/login');
	}

}
