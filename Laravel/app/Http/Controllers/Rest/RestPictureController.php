<?php

namespace Jetlag\Http\Controllers\Rest;

use Illuminate\Http\Request;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Business\ResourceAccess;
use Jetlag\Eloquent\Picture;
use Jetlag\Eloquent\Link;
use Storage;

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
  * Update the avatar for the user.
  *
  * @param  Request  $request
  * @return Response
  */
  public function upload(Request $request)
  {
    $pictureId = $request->input('picture_id');
    $picture = Picture::find($pictureId);
    if (!$picture) {
      abort(404);
    }

    // TODO use when the authoring is done properly
    // ResourceAccess::wantsToWriteResource($picture->author_id);
    $uploadedFile = $request->file('picture_file');
    if (!$uploadedFile || !$uploadedFile->isValid()) {
      abort(400, 'wrong format');
    }

    $path = 'pix/' . $request->user()->id . '/pik' . $pictureId . '.' . $uploadedFile->guessExtension();
    Storage::put($path, file_get_contents($uploadedFile->getRealPath()));
    if ($picture->big_url) {
      $picture->big_url->url = $path;
      $picture->big_url->save();
    } else {
      $link = new Link;
      $link->fromUrl($path);
      $link->save();
      $picture->big_url()->associate($link);
    }
    $picture->save();
    $picture->load(Picture::$relationsToLoad);
    return $picture;
  }
}
