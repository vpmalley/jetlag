<?php

namespace Jetlag\Http\Controllers\Rest;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jetlag\Business\ArticleSearch;
use Jetlag\Business\Article;
use Jetlag\Business\ResourceAccess;
use Jetlag\Eloquent\Author;
use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;

class SearchArticleController extends Controller
{

  /**
   * Create a new Rest article controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Search
  *
  * @param  Request  $request
  * @return Response
  */
  public function search(Request $request)
  {
    $searchService = new ArticleSearch;
    $foundArticles = $searchService->search($request->input('query'));
    $articles = [];
    foreach ($foundArticles as &$storedArticle)
    {
      $article = new Article;
      $article->fromStoredArticle($storedArticle);
      if (ResourceAccess::canReadResource($storedArticle->is_public, $storedArticle->author_id) && !$storedArticle->is_draft) {
        $articles[] = $article->getForRest();
      }
    }
    return $articles;
  }

  /**
   * Checks whether the logged in user can read the article. Rejects with error 403 otherwise.
   *
   * @param Article article the article to be read
   */
  public function wantsToReadArticle($article)
  {
    if (!$article->isPublic() && !Author::isReader(Auth::user()->id, $article->getAuthorId()))
    {
      abort(403);
    }
  }
}
