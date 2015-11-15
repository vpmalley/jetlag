<?php

namespace Jetlag\Http\Controllers\Rest;

use Validator;

use Illuminate\Http\Request;

use Jetlag\Http\Requests;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\Article;
use Log;

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
    if ($request->ajax()) {
    Log::debug('ajax: yes');
    }
    Log::debug('title: ' . $request->json('title'));
    Log::debug('title: ' . $request->title);
    $this->validate($request, Article::$rules); // TODO: own validator actually returning a 400 if the format is wrong
    $article = new Article;
    $article->fromRequest($request->input('title'), $request->input('descriptionText', ''), $request->input('isDraft', TRUE));
    $article->updateAuthorUsers($request->input('authorUserIds'));
    // TODO descriptionMedia
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
    $article->setTitle($request->json('title', $article->getTitle()));
    $article->setDescriptionText($request->json('descriptionText', $article->getDescriptionText()));
    if ($request->json('descriptionMediaId'))
    {
      // TODO set descriptionPicture based on the id, and update its url
    } else
    {
      $article->setDescriptionMediaUrl($request->json('descriptionMediaUrl', $article->getDescriptionMediaUrl()));
    }
    $article->setIsDraft($request->json('isDraft', $article->isDraft()));
    if ($request->json('authorUserIds'))
    {
      $article->updateAuthorUsers($request->json('authorUserIds'));
    }
    $article->persist();
    return ['id' => $article->getId()];
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
