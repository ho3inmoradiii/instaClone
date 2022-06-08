<?php

namespace App\Repositories\Eloquent;

use App\Invitation;
use App\Repositories\Contracts\IInvitation;
use App\Repositories\Eloquent\BaseRepository;

Class InvitationRepository extends BaseRepository implements IInvitation
{
    public function model()
    {
        return Invitation::class;
    }

    public function addUserToTeam($team,$userId)
    {
        $team->members()->attach($userId);
    }

    public function removeUserFromTeam($team,$userId)
    {
        $team->members()->detach($userId);
    }
}
