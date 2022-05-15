<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request,[
            'image' => ['required','mimes:jpeg,gif,bmp,png','max:2048']
        ]);

        $image = $request->file('image');
        $image_path = $image->getPathname();

        $fileName = time().'_'.preg_replace('/\s+/','_', strtolower($image->getClientOriginalName()));

        $tmp = $image->storeAs('uploads/original',$fileName,'tmp');

        $design = auth()->user()->designs()->create([
            'image' => $fileName,
            'disk' => config('site.upload_disk')
        ]);
        $this->dispatch(new UploadImage($design));

        return response()->json($design,200);
    }
}
