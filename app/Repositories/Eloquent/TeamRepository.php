<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ITeam;
use App\Repositories\Eloquent\BaseRepository;
use App\Team;

Class TeamRepository extends BaseRepository
{
    public function model()
    {
        return Team::class;
    }

    public function fetchUserTeams()
    {
        return auth()->user()->teams();
    }
}
