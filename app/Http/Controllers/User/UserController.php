<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->all();
        return UserResource::collection($users);
    }
}
