<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IUser;
use App\User;
use App\Repositories\Eloquent\BaseRepository;

Class UserRepository extends BaseRepository implements IUser
{
    public function model()
    {
        return User::class;
    }

    public function getUserByEmail($email)
    {
        return $this->model()::where('email',$email)->first();
    }
}
