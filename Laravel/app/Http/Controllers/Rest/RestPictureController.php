<?php

namespace Jetlag\Http\Controllers\Rest;

use Illuminate\Http\Request;
use Jetlag\Http\Controllers\Controller;
use Jetlag\Eloquent\Picture;
use Log;
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

    if ($picture) { // TODO check the user's rights
      $uploadedFile = $request->file('picture_file');
      if ($uploadedFile && $uploadedFile->isValid()) {
        $path = 'pix/' . $request->user()->id . '/pik' . $pictureId . '.' . $uploadedFile->guessExtension();
        Storage::put($path, file_get_contents($uploadedFile->getRealPath()));
        $picture->url = $path;
        $picture->save();
        return response()->json(['picture_id' => $pictureId, 'url' => Storage::url($path)], 200);
      } else {
        abort(400, 'wrong format');
      }
    } else {
      abort(404);
    }
  }
}
