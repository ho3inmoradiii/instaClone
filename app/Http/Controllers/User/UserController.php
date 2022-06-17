<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\criteria\EagerLoad;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();
        return UserResource::collection($users);
    }

    public function findByUsername($username)
    {
        $user = $this->users->findWhereFirst('username',$username);
        return new UserResource($user);
    }
}
