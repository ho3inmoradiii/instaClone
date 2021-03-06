<?php

namespace App\Http\Controllers\User;

use App\design;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\criteria\EagerLoad;
use App\Repositories\Eloquent\criteria\IsLive;
use App\Repositories\Eloquent\criteria\LatestFirst;
use App\Repositories\Eloquent\criteria\ForUser;
use App\Repositories\Eloquent\DesignRepository;
use App\Repositories\Eloquent\TeamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected $designs;
    protected $team;

    public function __construct(DesignRepository $designs,TeamRepository $team)
    {
        $this->designs = $designs;
        $this->team = $team;
    }

    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
//            new ForUser(2),
            new EagerLoad(['user','comments'])
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
        $validation['team_id'] = $request->team;

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

        return response()->json(['message' => '?????? ?????? ???? ???????????? ?????? ????'],200);
    }

    public function like($id)
    {
        $total = $this->designs->like($id);

        return response()->json(['message' => '???????????? ????????','total' => $total],200);
    }

    public function checkIfUserHasLiked($designId)
    {
        $isLiked = $this->designs->isLikedByUser($designId);
        return response()->json(['isLiked' => $isLiked],200);
    }

    public function search(Request $request)
    {
        $designs = $this->designs->search($request);
        return DesignResource::collection($designs);
    }

    public function findDesignBySlug($slug)
    {
        $design = $this->designs->withCriteria([new IsLive(),new EagerLoad(['comments'])])->findWhereFirst('slug',$slug);
        return new DesignResource($design);
    }

    public function getForTeam($id)
    {
        $designs = $this->designs->withCriteria([new IsLive()])->findWhere('team_id',$id);
        return DesignResource::collection($designs);
    }

    public function getForUser($id)
    {
        $designs = $this->designs
//            ->withCriteria([new IsLive()])
            ->findWhere('user_id',$id);
        return DesignResource::collection($designs);
    }

    public function userOwnsDesign($id)
    {
        $design = $this->designs->withCriteria(new ForUser(auth()->id()))->findWhereFirst('id',$id);
        return new DesignResource($design);
    }


}
