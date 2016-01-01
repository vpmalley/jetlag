<?php

namespace Jetlag\Http\Controllers\Rest;

use Validator;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\Article;
use Jetlag\Business\Picture;
use Jetlag\Eloquent\Author;
use Jetlag\Eloquent\Article as StoredArticle;
use Log;

class RestArticleController extends Controller
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
  * Display a listing of the resource for the logged in user.
  *
  * @return Response
  */
  public function index()
  {
    $articles = Article::getAllForUser(Auth::user()->id);
    foreach ($articles as &$article)
    {
      $article = $article->getForRestIndex();
    }
    return $articles;
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
  public function create()
  {
    // do not fill, but leave it for cookies
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  Request  $request
  * @return Response
  */
  public function store(Request $request)
  {
    $this->validate($request, Article::$rules); // TODO: own validator actually returning a 400 if the format is wrong
    $article = new Article;
    $article->fromRequest($request->input('title'), $request->input('descriptionText', ''), $request->input('isDraft', TRUE));

    $newAuthorUsers = [];
    if ($request->has('authorUsers'))
    {
      $newAuthorUsers = $request->input('authorUsers');
    }
    $newAuthorUsers[Auth::user()->id] = 'owner';
    $article->updateAuthorUsers($newAuthorUsers);

    if ($request->has('descriptionMedia'))
    {
      $article->setDescriptionPicture($this->extractPicture(new Picture, $request));
    }
    $article->persist();
    return ['id' => $article->getId()];
  }

  /**
  * Display the specified resource.
  *
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function show($storedArticle)
  {
    $article = new Article;
    $article->fromStoredArticle($storedArticle);
    $this->wantsToReadArticle($article);
    return $article->getForRest();
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function edit($storedArticle)
  {
    $article = new Article;
    $article->fromStoredArticle($storedArticle);
    $this->wantsToReadArticle($article);
    return $article->getForRest();
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  Request  $request
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function update(Request $request, $storedArticle)
  {
    $article = new Article;
    $article->fromStoredArticle($storedArticle);
    $this->wantsToWriteArticle($article);
    $this->validate($request, Article::$rules);
    $article->setTitle($request->input('title', $article->getTitle()));
    $article->setDescriptionText($request->input('descriptionText', $article->getDescriptionText()));
    if ($request->has('descriptionMedia'))
    {
      if ($article->hasDescriptionPicture())
      {
        $picture = $article->getDescriptionPicture();
      } else
      {
        $picture = new Picture;
      }
      $article->setDescriptionPicture($this->extractPicture($picture, $request));
    }
    $article->setIsDraft($request->input('isDraft', $article->isDraft()));
    if ($request->has('authorUsers'))
    {
      $newAuthorUsers = $request->input('authorUsers');
      $newAuthorUsers[Auth::user()->id] = Author::getRole($article->getAuthorId(), Auth::user()->id);
      $article->updateAuthorUsers($newAuthorUsers);
    }
    $article->persist();
    return ['id' => $article->getId()];
  }

  /**
   * Extracts the picture from the request
   *
   * @param  Jetlag\Business\Picture  $picture
   * @param  Request  $request
   * @return  Jetlag\Business\Picture the extracted picture
   */
  public function extractPicture(Picture $picture, Request $request)
  {
    $picture->setId($request->input('descriptionMedia.id', -1));
    if ($request->has('descriptionMedia.smallUrl'))
    {
      $picture->setSmallDisplayUrl($request->input('descriptionMedia.smallUrl'));
    }
    if ($request->has('descriptionMedia.url'))
    {
      $picture->setMediumDisplayUrl($request->input('descriptionMedia.url'));
    }
    if ($request->has('descriptionMedia.mediumUrl'))
    {
      $picture->setMediumDisplayUrl($request->input('descriptionMedia.mediumUrl'));
    }
    if ($request->has('descriptionMedia.bigUrl'))
    {
      $picture->setBigDisplayUrl($request->input('descriptionMedia.bigUrl'));
    }
    $picture->setAuthorId(-1); // TODO use logged in user
    return $picture;
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function destroy($storedArticle)
  {
    $article = new Article;
    $article->fromStoredArticle($storedArticle);
    $this->wantsToOwnArticle($article);
    $article->delete();
    return 'deleted ' . $article->getId();
  }

  /**
   * Checks whether the logged in user can modify the article as an owner. Rejects with error 403 otherwise.
   *
   * @param Article article the article to be owned
   */
  public function wantsToOwnArticle($article)
  {
    if (!Author::isOwner(Auth::user()->id, $article->getAuthorId()))
    {
      abort(403);
    }
  }

  /**
   * Checks whether the logged in user can write the article. Rejects with error 403 otherwise.
   *
   * @param Article article the article to be written
   */
  public function wantsToWriteArticle($article)
  {
    if (!Author::isWriter(Auth::user()->id, $article->getAuthorId()))
    {
      abort(403);
    }
  }

  /**
   * Checks whether the logged in user can read the article. Rejects with error 403 otherwise.
   *
   * @param Article article the article to be read
   */
  public function wantsToReadArticle($article)
  {
    if (!Author::isReader(Auth::user()->id, $article->getAuthorId()))
    {
      abort(403);
    }
  }
}
