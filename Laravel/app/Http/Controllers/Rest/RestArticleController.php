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
<<<<<<< HEAD
    $this->validate($request, Article::$creationRules); // TODO: own validator actually returning a 400 if the format is wrong
=======
    $validator = Validator::make($request->all(), Article::$rules);
    if ($validator->fails()) {
      abort(400);
    }
    if (null == Auth::user())
    {
      abort(403);
    }

>>>>>>> develop
    $article = new Article;
    $article->fromRequest($request->input('title'));

    $article->setDescriptionText($request->input('description_text', ''));
    $article->setIsDraft($request->input('is_draft', TRUE));
    $article->setIsPublic($request->input('is_public', FALSE));

    $newAuthorUsers = [];
    if ($request->has('author_users'))
    {
      $newAuthorUsers = $request->input('author_users');
    }
    $newAuthorUsers[Auth::user()->id] = 'owner';
    $article->updateAuthorUsers($newAuthorUsers);

    if ($request->has('description_media'))
    {
      $storedPicture = new Picture;
      $storedPicture->extract($request->input('description_media'));
      $picture = new BPicture;
      $picture->fromStoredPicture($storedPicture);
      $article->setDescriptionPicture($picture);
    }

    if ($request->has('paragraphs'))
    {
      foreach ($request->input('paragraphs') as $paragraphSubRequest) {
        if (array_key_exists('id', $paragraphSubRequest))
        {
          abort(400);
        }
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
    $this->wantsToReadArticle($storedArticle);
    $article->fromStoredArticle($storedArticle);
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
    $this->wantsToReadArticle($storedArticle);
    $article->fromStoredArticle($storedArticle);
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
    $this->wantsToWriteArticle($storedArticle);
    $article->fromStoredArticle($storedArticle);
<<<<<<< HEAD
    $this->wantsToWriteArticle($article);
    $this->validate($request, Article::$updateRules);
=======
    $validator = Validator::make($request->all(), Article::$rules);
    if ($validator->fails()) {
      abort(400);
    }
>>>>>>> develop
    $article->setTitle($request->input('title', $article->getTitle()));
    $article->setDescriptionText($request->input('description_text', $article->getDescriptionText()));
    if ($request->has('description_media'))
    {
      if ($article->hasDescriptionPicture())
      {
        $picture = $article->getDescriptionPicture();
      } else
      {
        $picture = new Picture;
        $storedPicture = new Picture;
        $storedPicture->extract($request->input('description_media'));
        $picture = new BPicture;
        $picture->fromStoredPicture($storedPicture);
        $article->setDescriptionPicture($picture);
      }
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
    $this->wantsToOwnArticle($storedArticle);
    $storedArticle->delete();
    return ['id' => $storedArticle->id];
  }

  /**
  * Checks whether the logged in user can modify the article as an owner. Rejects with error 403 otherwise.
  *
  * @param StoredArticle storedArticle the article to be owned
  */
  public function wantsToOwnArticle($storedArticle)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!Author::isOwner($id, $storedArticle->author_id))
    {
      abort(403);
    }
  }

  /**
  * Checks whether the logged in user can write the article. Rejects with error 403 otherwise.
  *
  * @param StoredArticle storedArticle the article to be written
  */
  public function wantsToWriteArticle($storedArticle)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!Author::isWriter($id, $storedArticle->author_id))
    {
      abort(403);
    }
  }

  /**
  * Checks whether the logged in user can read the article. Rejects with error 403 otherwise.
  *
  * @param StoredArticle storedArticle the article to be read
  */
  public function wantsToReadArticle($storedArticle)
  {
    $id = Auth::user() ? Auth::user()->id : -1;
    if (!$storedArticle->is_public && !Author::isReader($id, $storedArticle->author_id))
    {
      abort(403);
    }
  }
}
