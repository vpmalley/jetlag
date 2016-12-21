<?php

namespace Jetlag\Http\Controllers\Rest;

use Validator;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\ResourceAccess;
use Jetlag\Business\Article;
use Jetlag\Eloquent\Article as StoredArticle;
use Jetlag\Eloquent\Link;
use Jetlag\Eloquent\Paragraph;
use Jetlag\Eloquent\Picture;
use Jetlag\Eloquent\Place;
use Jetlag\Eloquent\Author;
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
    $this->middleware('auth.rest', ['except' => 'show']);
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
    $validator = Validator::make($request->all(), StoredArticle::$creationRules);
    if ($validator->fails()) {
      abort(400, $validator->errors());
    }
    if (null == Auth::user())
    {
      abort(403);
    }

    $requestBody = array_merge(StoredArticle::$default_fillable_values, $request->all());
    $article = new StoredArticle;
    $article->extract($requestBody);
    $article->loadRelations();
    $article->addUrl();

    return response($article, 201);
  }

  /**
  * Display the specified resource.
  *
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function show($storedArticle)
  {
    // ResourceAccess::wantsToReadResource($storedArticle->is_public, $storedArticle->author_id);
    $storedArticle->loadRelations();
    $storedArticle->addUrl();
    return $storedArticle;
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function edit($storedArticle)
  {
    // ResourceAccess::wantsToReadResource($storedArticle->is_public, $storedArticle->author_id);
    $storedArticle->loadRelations();
    $storedArticle->addUrl();
    return $storedArticle;
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
    // ResourceAccess::wantsToWriteResource($storedArticle->author_id);
    $validator = Validator::make($request->all(), StoredArticle::$updateRules);
    if ($validator->fails()) {
      abort(400, $validator->errors());
    }

    $requestBody = $request->all();
    $storedArticle->extract($requestBody);
    $storedArticle->loadRelations();
    $storedArticle->addUrl();

    return response($storedArticle, 200);
  }

  /**
  * Gets the subrequest value matching the key
  * @param array subRequest a part of the request
  * @param string key the key matching the expected value
  * @param default the default value when no value matches the key
  *
  */
  public static function get($subRequest, $key, $default = null)
  {
    if (array_key_exists($key, $subRequest))
    {
      return $subRequest[$key];
    } else
    {
      return $default;
    }
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  Jetlag\Eloquent\Article $storedArticle
  * @return Response
  */
  public function destroy($storedArticle)
  {
    ResourceAccess::wantsToOwnResource($storedArticle->author_id);
    if ($storedArticle->delete())
    {
      return ['id' => $storedArticle->id];
    } else
    {
      abort(500, 'not deleted');
    }
  }
}
