<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IUser;
use App\User;

Class UserRepository implements IUser
{

    public function all()
    {
        return User::all();
    }
}
