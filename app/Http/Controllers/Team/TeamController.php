<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Repositories\Eloquent\InvitationRepository;
use App\Repositories\Eloquent\TeamRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    protected $team;
    protected $user;
    protected $invitation;

    public function __construct(TeamRepository $team,UserRepository $user,InvitationRepository $invitation)
    {
        $this->team = $team;
        $this->user = $user;
        $this->invitation = $invitation;
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request,[
            'name' => ['required','string','max:80','unique:teams,name']
        ]);

        $team = $this->team->create([
            'name' => $request->name,
            'owner_id' => auth()->id(),
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function update(Request $request,$id)
    {
        $team = $this->team->find($id);
        $this->authorize('update',$team);

        $validated = $this->validate($request,[
            'name' => ['required','string','max:80','unique:teams,name']
        ]);

        $team = $this->team->update($id,[
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function findById($id)
    {
        $team = $this->team->find($id);
        return new TeamResource($team);
    }

    public function fetchUserTeams()
    {
        $users = $this->team->fetchUserTeams();
        return TeamResource::collection($users);
    }

    public function removeFromTeam($teamId,$userId)
    {
        $team = $this->team->find($teamId);
        $user = $this->user->find($userId);

        if ($user->isOwnerOfTeam($team))
        {
            return response()->json(['message' => 'شما ایجاد کننده گروه هستید'],401);
        }

        if (!auth()->user()->isOwnerOfTeam($team) && auth()->id() !== $userId)
        {
            return response()->json(['message' => 'شما اجازه این کار را ندارید'],401);
        }

        $this->invitation->removeUserFromTeam($team,$userId);
        return response()->json(['message' => 'موفقیت آمیز'],200);
    }
}
