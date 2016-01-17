<?php

namespace Jetlag\Http\Controllers\Rest;

use Validator;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\Article;
use Jetlag\Eloquent\Picture;
use Jetlag\Business\Picture as BPicture;
use Jetlag\Eloquent\Paragraph;
use Jetlag\Eloquent\Author;
use Jetlag\Eloquent\Link;
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
      $storedPicture = $this->extractPicture(new Picture, $request->input('descriptionMedia'));
      $picture = new BPicture;
      $picture->fromStoredPicture($storedPicture);
      $article->setDescriptionPicture($picture);
    }

    if ($request->has('paragraphs'))
    {
      foreach ($request->input('paragraphs') as $paragraph) {
        $paragraph = $this->extractParagraph(new Paragraph, $paragraph);
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
        $storedPicture = $this->extractPicture(new Picture, $request->input('descriptionMedia'));
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
    $article->persist();
    return ['id' => $article->getId()];
  }

  /**
   * Extracts the picture from the subrequest
   *
   * @param  Jetlag\Eloquent\Picture  $picture
   * @param  array  $subRequest
   * @return  Jetlag\Eloquent\Picture the extracted picture
   */
  public function extractPicture(Picture $picture, $subRequest)
  {
    $picture->id = $this->get($subRequest, 'id', -1);
    $picture->authorId = -1; // TODO authoring refacto

    if (array_key_exists('small_url', $subRequest))
    {
      $smallUrlLink = $this->extractLink(new Link, $subRequest['small_url']);
      $picture->smallUrl()->associate($smallUrlLink);
    }

    if (array_key_exists('url', $subRequest))
    {
      $mediumUrlLink = $this->extractLink(new Link, $subRequest['url']);
      $picture->mediumUrl()->associate($mediumUrlLink);
    }

    if (array_key_exists('medium_url', $subRequest))
    {
      $mediumUrlLink = $this->extractLink(new Link, $subRequest['medium_url']);
      $picture->mediumUrl()->associate($mediumUrlLink);
    }

    if (array_key_exists('big_url', $subRequest))
    {
      $bigUrlLink = $this->extractLink(new Link, $subRequest['big_url']);
      $picture->bigUrl()->associate($bigUrlLink);
    }
    $picture->save();
    // TODO extract place
    return $picture;
  }

  /**
   * Extracts the link from the subrequest
   *
   * @param  Jetlag\Eloquent\Link  $link
   * @param  array  $subRequest
   * @return  Jetlag\Eloquent\Link the extracted link
   */
  public function extractLink(Link $link, $subRequest)
  {
    $link->fromUrl($this->get($subRequest, 'url'));
    $link->caption = $this->get($subRequest, 'caption', '');
    $link->save();
    return $link;
  }

  /**
   * Extracts the paragraph from the subrequest
   *
   * @param  Jetlag\Eloquent\Paragraph  $paragraph
   * @param  array  $subRequest
   * @return  Jetlag\Eloquent\Paragraph the extracted paragraph
   */
  public function extractParagraph(Paragraph $paragraph, $subRequest)
  {
    $paragraph->id = $this->get($subRequest, 'id', -1);
    $paragraph->title = $this->get($subRequest, 'title', '');
    $paragraph->weather = $this->get($subRequest, 'weather');
    $paragraph->date = $this->get($subRequest, 'date', '');
    $paragraph->isDraft = $this->get($subRequest, 'isDraft', true);
    $paragraph->authorId = -1; // TODO authoring refacto
    $paragraph->save();

    if (array_key_exists('block_content', $subRequest))
    {
      $picture = $this->extractPicture(new Picture, $subRequest['block_content']);
      $paragraph->blockContent()->associate($picture);
    }
    // TODO extract blockContent, place
    return $paragraph;
  }

  /**
   * Gets the subrequest value matching the key
   * @param array subRequest a part of the request
   * @param string key the key matching the expected value
   * @param default the default value when no value matches the key
   *
   */
  private function get($subRequest, $key, $default = null)
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
