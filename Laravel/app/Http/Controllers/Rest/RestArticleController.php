<?php

namespace Jetlag\Http\Controllers\Rest;

use Validator;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\Article;
use Jetlag\Business\Picture as BPicture;
use Jetlag\Eloquent\Article as StoredArticle;
use Jetlag\Eloquent\Author;
use Jetlag\Eloquent\Link;
use Jetlag\Eloquent\Paragraph;
use Jetlag\Eloquent\Picture;
use Jetlag\Eloquent\Place;
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
    $validator = Validator::make($request->all(), Article::$rules);
    if ($validator->fails()) {
      abort(400);
    }
    if (null == Auth::user())
    {
      abort(403);
    }

    $article = new Article;
    $article->fromRequest($request->input('title'));

    $article->setDescriptionText($request->input('descriptionText', ''));
    $article->setIsDraft($request->input('isDraft', TRUE));
    $article->setIsPublic($request->input('isPublic', FALSE));

    $newAuthorUsers = [];
    if ($request->has('authorUsers'))
    {
      $newAuthorUsers = $request->input('authorUsers');
    }
    $newAuthorUsers[Auth::user()->id] = 'owner';
    $article->updateAuthorUsers($newAuthorUsers);

    if ($request->has('descriptionMedia'))
    {
      $storedPicture = new Picture;
      $storedPicture->extract($request->input('descriptionMedia'));
      $picture = new BPicture;
      $picture->fromStoredPicture($storedPicture);
      $article->setDescriptionPicture($picture);
    }

    if ($request->has('paragraphs'))
    {
      foreach ($request->input('paragraphs') as $paragraphSubRequest) {
        $paragraphSubRequest = array_merge(Paragraph::$default_fillable_values, $paragraphSubRequest);
        $validator = Validator::make($paragraphSubRequest, Paragraph::$rules);
        if ($validator->fails()) {
          abort(400);
        }
        $paragraph = Paragraph::create($paragraphSubRequest);
        $paragraph->extract($paragraphSubRequest);
        $article->addParagraph($paragraph);
      }
    }
    $article->persist();
    return response()->json(['id' => $article->getId(), 'url' => $article->getWebUrl()], 201);
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
    $validator = Validator::make($request->all(), Article::$rules);
    if ($validator->fails()) {
      abort(400);
    }
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
        $storedPicture = new Picture;
        $storedPicture->extract($request->input('descriptionMedia'));
        $picture = new BPicture;
        $picture->fromStoredPicture($storedPicture);
        $article->setDescriptionPicture($picture);
      }
    }
    $article->setIsDraft($request->input('isDraft', $article->isDraft()));
    if ($request->has('authorUsers'))
    {
      $newAuthorUsers = $request->input('authorUsers');
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
    $article = new Article;
    $article->fromStoredArticle($storedArticle);
    $this->wantsToOwnArticle($article);
    $article->delete();
    return ['id' => $article->getId()];
  }

  /**
   * Checks whether the logged in user can modify the article as an owner. Rejects with error 403 otherwise.
   *
   * @param Article article the article to be owned
   */
  public function wantsToOwnArticle($article)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!Author::isOwner($id, $article->getAuthorId()))
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
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!Author::isWriter($id, $article->getAuthorId()))
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
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!$article->isPublic() && !Author::isReader($id, $article->getAuthorId()))
    {
      abort(403);
    }
  }
}
