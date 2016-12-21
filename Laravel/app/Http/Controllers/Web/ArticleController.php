<?php namespace Jetlag\Http\Controllers\Web;

use Jetlag\Http\Controllers\Controller;
use Jetlag\Eloquent\Article;
use Jetlag\Eloquent\Link;
use Jetlag\Business\ResourceAccess;

class ArticleController extends Controller {

  /*
  |--------------------------------------------------------------------------
  | Article Controller
  |--------------------------------------------------------------------------
  |
  |
  */


  /**
   * Create a new article controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //$this->middleware('author:articles,2');
  }

  /**
   * Redirects to the default user page
   *
   * @param  int  $id
   */
  public function getIndex($id)
  {
    return $this->getDisplay($id);
  }

  /**
   * Display the public profile
   *
   * @param  int  $id
   */
  public function getDisplay($id)
  {
    $article = Article::find($id);
    // ResourceAccess::wantsToReadResource($article->is_public, $article->author_id);
    return view('article', $article);
  }

  /** TODO: remove or merge in master **/
	public function getCreate($id)
	{
	 return view('articleCreator', ['id' => $id]);
	}

	public function getEdit()
	{
	 return view('articleEditor');
	}
}
