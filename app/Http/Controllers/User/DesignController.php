<?php

namespace App\Http\Controllers\User;

use App\design;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\criteria\IsLive;
use App\Repositories\Eloquent\criteria\LatestFirst;
use App\Repositories\Eloquent\criteria\ForUser;
use App\Repositories\Eloquent\DesignRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected $designs;

    public function __construct(DesignRepository $designs)
    {
        $this->designs = $designs;
    }

    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(2)
        ])->all();
        return DesignResource::collection($designs);
    }

    public function findDesign($id)
    {
        $design = $this->designs->find($id);
        return new DesignResource($design);
    }

    public function update(UpdateDesignRequest $request,$id)
    {
        $design = $this->designs->find($id);
        $this->authorize('update',$design);

        $validation = $request->validated();
        $validation['slug'] = Str::slug($request->title);

        $validation['is_live'] = !$design->upload_successful ? false : $request->is_live;

        $design = $this->designs->update($id,$validation);

        $this->designs->applyTag($id,$request->tags);

        return new DesignResource($design);
    }

    public function destroy($id)
    {
        $design = $this->designs->find($id);
        $this->authorize('delete',$design);

        foreach (['thumbnail','large','original'] as $size){
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            }
        }

        $this->designs->delete($id);

        return response()->json(['message' => 'پست شما با موفقیت حذف شد'],200);
    }
}
