<?php namespace Jetlag\Http\Controllers\Web;

use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\Article;
use Jetlag\Eloquent\Link;

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
    $article = Article::getById($id);
    return view('web.article.display', $article->getForDisplay());
  }
  
}
