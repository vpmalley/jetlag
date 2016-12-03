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
    $validator = Validator::make($request->all(), Article::$creationRules);
    if ($validator->fails()) {
      abort(400);
    }
    if (null == Auth::user())
    {
      abort(403);
    }

    $article = new StoredArticle;
    $article->extract($request->all());

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
    ResourceAccess::wantsToReadResource($storedArticle->is_public, $storedArticle->author_id);
    $storedArticle->loadRelations();
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
    ResourceAccess::wantsToReadResource($storedArticle->is_public, $storedArticle->author_id);
    $storedArticle->loadRelations();
    return $storeArticle;
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
    ResourceAccess::wantsToWriteResource($storedArticle->author_id);
    $validator = Validator::make($request->all(), Article::$updateRules);
    if ($validator->fails()) {
      abort(400);
    }

    $article = new Article;
    $article->fromStoredArticle($storedArticle);
    $article->setTitle($request->input('title', $article->getTitle()));
    $article->setDescriptionText($request->input('description_text', $article->getDescriptionText()));
    if ($request->has('description_media'))
    {
      $storedPicture = new Picture;
      $storedPicture->extract($request->input('description_media'));
      $article->setDescriptionPicture($storedPicture);
    }
    $article->setIsDraft($request->input('is_draft', $article->isDraft()));
    if ($request->has('author_users'))
    {
      $newAuthorUsers = $request->input('author_users');
      $newAuthorUsers[Auth::user()->id] = Author::getRole($article->getAuthorId(), Auth::user()->id);
      $article->updateAuthorUsers($newAuthorUsers);
    }

    if ($request->has('paragraphs'))
    {
      foreach ($request->input('paragraphs') as $paragraphSubRequest) {
        if (array_key_exists('id', $paragraphSubRequest))
        {
          $paragraph = Paragraph::find($paragraphSubRequest['id']);
          $paragraph->fill($paragraphSubRequest);
        } else
        {
          $paragraphSubRequest = array_merge(Paragraph::$default_fillable_values, $paragraphSubRequest);
          $paragraph = Paragraph::create($paragraphSubRequest);
        }
        $paragraph->extract($paragraphSubRequest);
        $article->addParagraph($paragraph);
      }
    }
    $article->persist();
    return ['id' => $article->getId()];
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
