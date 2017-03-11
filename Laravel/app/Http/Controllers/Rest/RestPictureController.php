<?php

namespace Jetlag\Http\Controllers\Rest;

use Illuminate\Http\Request;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\ResourceAccess;
use Jetlag\Eloquent\Picture;
use Jetlag\Eloquent\Link;
use Storage;
use Validator;
use Auth;

class RestPictureController extends Controller
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
    // $articles = Article::getAllForUser(Auth::user()->id);
    return [];
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
    $validator = Validator::make($request->all(), Picture::$rules);
    if ($validator->fails()) {
      abort(400);
    }
    if (null == Auth::user())
    {
      abort(403);
    }

    $picture = new Picture;
    $picture->extract($request->all());
    $picture->load(Picture::$relationsToLoad);
    return $picture;
  }

  /**
  * Display the specified resource.
  *
  * @param  Jetlag\Eloquent\Picture $storedPicture
  * @return Response
  */
  public function show($storedPicture)
  {
    // ResourceAccess::wantsToReadResource($storedPicture->is_public, $storedPicture->author_id);
    $storedPicture->load(Picture::$relationsToLoad);
    return $storedPicture;
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  Jetlag\Eloquent\Picture $storedPicture
  * @return Response
  */
  public function edit($storedPicture)
  {
    // ResourceAccess::wantsToReadResource($storedPicture->is_public, $storedPicture->author_id);
    return $storedPicture;
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  Request  $request
  * @param  Jetlag\Eloquent\Picture $storedPicture
  * @return Response
  */
  public function update(Request $request, $storedPicture)
  {
    // ResourceAccess::wantsToWriteResource($storedArticle->author_id);
    $validator = Validator::make($request->all(), Picture::$rules);
    if ($validator->fails()) {
      abort(400);
    }
    $storedPicture->extract($request->all());
    $storedPicture->load(Picture::$relationsToLoad);
    return $storedPicture;
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  Jetlag\Eloquent\Picture $storedPicture
  * @return Response
  */
  public function destroy($storedPicture)
  {
    // ResourceAccess::wantsToOwnResource($storedPicture->author_id);
    if ($storedPicture->delete())
    {
      return ['id' => $storedPicture->id];
    } else
    {
      abort(500, 'not deleted');
    }
  }

  /**
  * Update the avatar for the user.
  *
  * @param  Jetlag\Eloquent\Picture $storedPicture
  * @param  Request  $request
  * @return Response
  */
  public function upload($storedPicture, Request $request)
  {
    // TODO use when the authoring is done properly
    // ResourceAccess::wantsToWriteResource($picture->author_id);
    $uploadedFile = $request->file('file');
    if (!$uploadedFile || !$uploadedFile->isValid()) {
      abort(400, 'wrong format');
    }

    $path = 'pix/' . $request->user()->id . '/pik' . $storedPicture->id . '.' . $uploadedFile->guessExtension();
    Storage::put($path, file_get_contents($uploadedFile->getRealPath()));
    if ($storedPicture->big_url) {
      $storedPicture->big_url->url = $path;
      $storedPicture->big_url->caption = $request->input('caption');
      $storedPicture->big_url->save();
    } else {
      $link = new Link;
      $link->fromUrl($path);
      $link->caption = $request->input('caption');
      $link->save();
      $storedPicture->big_url()->associate($link);
    }
    $storedPicture->save();
    $storedPicture->load(Picture::$relationsToLoad);
    return $storedPicture;
  }

  /**
  * Update the avatar for the user.
  *
  * @param  Request  $request
  * @return Response
  */
  public function uploadNew(Request $request)
  {
    if (null == Auth::user())
    {
      abort(403);
    }

    $uploadedFile = $request->file('file');
    if (!$uploadedFile || !$uploadedFile->isValid()) {
      abort(400, 'wrong format');
    }
    $picture = new Picture;
    $picture->save();

    $path = 'pix/' . $request->user()->id . '/pik' . $picture->id . '.' . $uploadedFile->guessExtension();
    Storage::put($path, file_get_contents($uploadedFile->getRealPath()));

    $link = new Link;
    $link->fromUrl($path);
    $link->caption = $request->input('caption');
    $link->save();
    $picture->big_url()->associate($link);

    $picture->save();
    $picture->load(Picture::$relationsToLoad);
    return $picture;
  }
}
