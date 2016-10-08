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
  public function update(Request $request)
  {
    $pictureId = $request->input('pictureId');
    $picture = Picture::find($pictureId);

    if ($picture) {
      $uploadedFile =  $request->file('pictureFile');
      $path = 'pix/' . $request->user()->id . '/pik' . $pictureId . '.' . $uploadedFile->guessExtension();
      if ($uploadedFile->isValid()) {
        Storage::put($path, file_get_contents($uploadedFile->getRealPath()));
      }
      $picture->url = $path;
      $picture->save();
      return 'stored pic ' . $pictureId;
    } else {
      return 'no pic ' . $pictureId;
    }
  }
}
