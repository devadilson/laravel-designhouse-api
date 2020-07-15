<?php

namespace App\Http\Controllers\Teams;

use Illuminate\Http\Request;
use App\Jobs\UploadTeamImage;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
  protected $teams;

  public function upload(Request $request)
  {
    // validate the request
    $this->validate($request, [
      'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:2048']
    ]);

    // get the image
    $image = $request->file('image');
    $image_path = $image->getPathName();

    // get the original file name and replace any spaces with _
    // Business Cards.png = timestamp()_business_cards.png
    $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

    // move the image to the temporary location (tmp)
    $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

    // create the database record for the team

    $team = auth()->user()->teams()->create([
      'owner_id' => auth()->id(),
      'image' => $filename,
      'disk' => config('site.upload_disk')
    ]);

    //$team = $this->teams->create([
    //    'user_id' => auth()->id(),
    //    'image' => $filename,
    //    'disk' => config('site.upload_disk')
    //]);

    // dispatch a job to handle the image manipulation
    $this->dispatch(new UploadTeamImage($team));

    return response()->json($team, 200);
  }
}
