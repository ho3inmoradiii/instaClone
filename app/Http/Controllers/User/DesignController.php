<?php

namespace App\Http\Controllers\User;

use App\design;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    public function update(UpdateDesignRequest $request,$id)
    {
        $design = design::findOrFail($id);
        $this->authorize('update',$design);

        $validation = $request->validated();
        $validation['slug'] = Str::slug($request->title);

        $validation['is_live'] = !$design->upload_successful ? false : $request->is_live;

        $design->update($validation);

        $design->retag($request->tags);

        return new DesignResource($design);
    }

    public function destroy($id)
    {
        $design = design::findOrFail($id);
        $this->authorize('delete',$design);

        foreach (['thumbnail','large','original'] as $size){
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            }
        }

        $design->delete();

        return response()->json(['message' => 'پست شما با موفقیت حذف شد'],200);
    }
}
