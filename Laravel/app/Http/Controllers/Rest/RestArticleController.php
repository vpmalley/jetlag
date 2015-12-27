<?php

namespace Jetlag\Http\Controllers\Rest;

use Validator;

use Illuminate\Http\Request;

use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\Article;
use Jetlag\Business\Picture;

class RestArticleController extends Controller
{

  /**
  * Display a listing of the resource for the logged in user.
  *
  * @return Response
  */
  public function index()
  {
    $articles = Article::getAllForUser(1); // TODO use logged in user
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
    $article->updateAuthorUsers($request->input('authorUsers'), []); // TODO default with logged user as owner

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
  * @param  int  $id
  * @return Response
  */
  public function show($id)
  {
    return Article::getById($id)->getForRest();
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function edit($id)
  {
    return Article::getById($id)->getForRest();
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  Request  $request
  * @param  int  $id
  * @return Response
  */
  public function update(Request $request, $id)
  {
    $this->validate($request, Article::$rules);
    $article = Article::getById($id);
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
      $article->updateAuthorUsers($request->input('authorUsers'));
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
  * @param  int  $id
  * @return Response
  */
  public function destroy($id)
  {
    Article::getById($id)->delete();
    return 'deleted ' . $id;
  }
}
